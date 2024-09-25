<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Deck;
use App\Form\DeckType;
use App\Entity\Picture;
use App\Form\SearchCardType;
use App\HttpClient\ApiHttpClient;
use Doctrine\ORM\Query\Parameter;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeckController extends AbstractController
{
    #[Route('/deck', name: 'app_deck')]
    public function index(DeckRepository $deckRepository): Response
    {
        $decks = $deckRepository->findDecks();

        return $this->render('deck/index.html.twig', [
            'decks' => $decks,
        ]);
    }

    #[Route('/deck/{idDeck}/add-card', name: 'card_add_to_deck', methods: 'POST')]
    public function addCard(CardRepository $cardRepository, EntityManagerInterface $entityManager, Request $request, Card $card = null){
        
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $attribute = filter_input(INPUT_POST, 'attribute', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $level = filter_input(INPUT_POST, 'level', FILTER_SANITIZE_NUMBER_INT);
        $race = filter_input(INPUT_POST, 'race', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $effect = filter_input(INPUT_POST, 'effect', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $att = filter_input(INPUT_POST, 'att', FILTER_SANITIZE_NUMBER_INT);
        $def = filter_input(INPUT_POST, 'def', FILTER_SANITIZE_NUMBER_INT);
        $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_NUMBER_INT);
        $scale = filter_input(INPUT_POST, 'scale', FILTER_SANITIZE_NUMBER_INT);
        $linkmarker = filter_input(INPUT_POST, 'linkmarker', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $refCard = filter_input(INPUT_POST, 'refCard', FILTER_SANITIZE_NUMBER_INT);
        $typecard = filter_input(INPUT_POST, 'typecard', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $card = new Card();
        //est ce que la carte est déjà en base de donnée
        $cardcheck = $cardRepository->findCardByRefCard($refCard);
        //si oui
        if ($cardcheck){

            $deckId = $request->attributes->get('idDeck');
            $deck = $entityManager->getRepository(Deck::class)->find($deckId);
            //dd($cardcheck);
            $cardcheck[0]->addDeck($deck);
            $deck->addCard($cardcheck[0]);
            $entityManager->persist($deck);
            $entityManager->flush();
    
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
        }
        else{
        
            if ($name && $race && $effect && $picture){
                $card->setAttribute($attribute);
                $card->setName($name);
                $card->setRace($race);
                $card->setEffect($effect);
                $card->setPicture($picture);
                $card->setRefCard($refCard);
                $card->setTypecard($typecard);
                if ($att){
                    $card->setAtt($att);
                    if($scale){
                        $card->setLevel($level);
                        $card->setScale($scale);
                        $card->setDef($def);
                    }
                    elseif($link){
                        $card->setLink($link);
                        $card->setLinkMarker($linkmarker);
                    }
                    else{
                        $card->setLevel($level);
                        $card->setDef($def);
                    }
                }
                
                $entityManager->persist($card);
                $entityManager->flush();
                
            }
            $deckId = $request->attributes->get('idDeck');
            $deck = $entityManager->getRepository(Deck::class)->find($deckId);
            //dd($card);
            $card->addDeck($deck);
            $deck->addCard($card);
            $entityManager->persist($deck);
            $entityManager->flush();
    
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
        }
    }

    #[Route('/deck/{id}/{idDeck}/delete-card', name: 'card_delete_to_deck')]
    public function deleteCard(EntityManagerInterface $entityManager, Request $request){

            $idCard = $request->attributes->get('id');
            $deckId = $request->attributes->get('idDeck');
            $deck = $entityManager->getRepository(Deck::class)->find($deckId);
            $card = $entityManager->getRepository(Card::class)->find($idCard);
            //dd($cardcheck);
            $card->removeDeck($deck);
            $entityManager->persist($card);
            $entityManager->flush();
    
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
        
    }

    #[Route('/deck/new', name: 'new_deck')]
    #[Route('/deck/{id}/edit', name: 'update_deck')]
    public function new(EntityManagerInterface $entityManager, Request $request, Card $card = null,Deck $deck = null, ApiHttpClient $apiHttpClient): Response
    {
        //dd($deck);
        if($deck == null){
            $deck = new deck();
        }
        
        $formDeck = $this->createForm(DeckType::class,$deck);

        $card = new Card();
        $form = $this->createForm(SearchCardType::class,$card);

        $cards = $apiHttpClient->getCards();

        $formDeck->handleRequest($request);
        
        if($formDeck->isSubmitted() && $formDeck->isValid()){
            
            if($request->request->all()){

                $dec = $request->request->all() ;
                //récupération de la valeur dans picture
                $picture = $dec['deck']['picture'];
                //handleRequest prend ce qu'il y a dans l'imput formulaire
                
                $infocard = $apiHttpClient->getCardsById($picture);
                //dd($infocard);
                foreach ($infocard['data'] as $detail){
                    $this->addImage($detail, $entityManager);
                }
            }

            $deck = $formDeck->getData();
            if ($picture){
                $deck->setPicture($picture);
            }
            $entityManager->persist($deck);
            $entityManager->flush();

            return $this->redirectToRoute('app_deck');
        }elseif ($formDeck->isSubmitted()){
            $deck = $formDeck->getData();
            $deck->setPicture('null');
            $entityManager->persist($deck);
            $entityManager->flush();
        }
        
        return $this->render('deck/new.html.twig', [
            'formSearchCard' => $form,
            'cards' => $cards,
            'formDeck' => $formDeck,
            'deck' => $deck,
        ]);
    }

    private function addImage(array $detail, EntityManagerInterface $entityManager) {
        $route = $detail['card_images'][0]['image_url_cropped'];
        $imageContent = file_get_contents($route);
        $imagePath = '../public/imagesDeck/' . $detail['id'] . '.jpg';
        if (!file_exists($imagePath)) {
            file_put_contents($imagePath, $imageContent);
            $picture = new Picture();
            $picture->setRefCard($detail['id']);
            $picture->setRoute($imagePath);
            $entityManager->persist($picture);
            $entityManager->flush();
        }
    }

    
}
