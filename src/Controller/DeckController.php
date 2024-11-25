<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\Post;
use App\Entity\DeckCard;
use App\Form\DeckType;
use App\Form\PostType;
use App\Entity\Picture;
use App\Form\SearchCardType;
use App\HttpClient\ApiHttpClient;
use Doctrine\ORM\Query\Parameter;
use App\Repository\CardRepository;
use App\Repository\DeckCardRepository;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/deck/add-card/{idDeck}', name: 'card_add_to_deck', methods: 'POST')]
    public function addCard(CardRepository $cardRepository, DeckCardRepository $deckCardRepository, EntityManagerInterface $entityManager, Request $request, DeckCard $deckCard = null, Card $card = null){
        
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
        
        $deckCard = new DeckCard();
        $card = new Card();
        //est ce que la carte est déjà en base de donnée
        $cardcheck = $cardRepository->findCardByRefCard($refCard);
        //si oui
        if ($cardcheck){


            $deckId = $request->attributes->get('idDeck');
            $deck = $entityManager->getRepository(Deck::class)->find($deckId);
            $deckCard = $deckCardRepository->findDeckCardsByCardAndDeck($cardcheck[0],$deck);
            if ($deckCard){
                $qtt = $deckCard[0]->getQtt();
                if($qtt<=3){
                    $deckCard->setQtt($qtt++);
                }
        
                return $this->redirectToRoute('update_deck', ['id' => $deckId]);
            }
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
            
            /*
            return $this->renderView('deck/_tempDeck.html.twig', [
                    'deck' => $deck
                ]);
            */
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
            
            $deckCard = new DeckCard();
            $deckCard->setDeck($deck);
            $deckCard->setCard($card);
            $deckCard->setQtt(1);
            $deckCard->setZone("main");

            //dd($card);
            $entityManager->persist($deckCard);
            $entityManager->flush();
    
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
        }
    }

    #[Route('/deck/delete-card/{id}/{idDeck}', name: 'card_delete_to_deck')]
    public function deleteCard(EntityManagerInterface $entityManager, Request $request){

            $idCard = $request->attributes->get('id');
            $deckId = $request->attributes->get('idDeck');
            $deck = $entityManager->getRepository(Deck::class)->find($deckId);
            $card = $entityManager->getRepository(Card::class)->find($idCard);
            //dd($cardcheck);
            $card->removeDeckCard($deck);
            $entityManager->persist($card);
            $entityManager->flush();
    
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
        
    }

    #[Route('/deck/new', name: 'new_deck')]
    #[Route('/deck/edit/{id}', name: 'update_deck')]
    public function new(EntityManagerInterface $entityManager, Request $request,DeckCard $deckCards, Card $card = null,Deck $deck = null, ApiHttpClient $apiHttpClient): Response
    {
        //dd($deck);
        if($deck == null){
            $deck = new deck();
        }
        //dd($deck);
        $formDeck = $this->createForm(DeckType::class,$deck);
        //dd($deck);
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

            return $this->redirectToRoute('app_deckUser');

        }elseif ($formDeck->isSubmitted()){
            $deck = $formDeck->getData();
            $deck->setPicture('null');
            $entityManager->persist($deck);
            $entityManager->flush();
        }

        $deckCards = $entityManager->getRepository(DeckCard::class)->findDeckCardsByDeck($deck);
        
        return $this->render('deck/new.html.twig', [
            'formSearchCard' => $form,
            'cards' => $cards,
            'formDeck' => $formDeck,
            'deck' => $deck,
            'deckCards' => $deckCards,
        ]);
    }

    #[Route('/deck/read/{id}', name: 'show_deck')]
    public function readDeck(EntityManagerInterface $entityManager, Request $request,DeckCard $deckCards = null, Deck $deck = null, Post $post = null): Response
    {
        $deckId = $request->attributes->get('id');
        $deck = $entityManager->getRepository(Deck::class)->find($deckId);

        $deckCards = $entityManager->getRepository(DeckCard::class)->findDeckCardsByDeck($deck);

        $post = new Post();
        $formPost = $this->createForm(PostType::class,$post);

        return $this->render('deck/show.html.twig', [
            'deck' => $deck,
            'deckCards' => $deckCards,
            'formPost' => $formPost,
        ]);
    }

    #[Route('/deck/delete/{idDeck}', name: 'delete_deck')]
    public function deleteDeck(EntityManagerInterface $entityManager, Request $request,Deck $deck = null): Response
    {
        $deckId = $request->attributes->get('idDeck');
        $deck = $entityManager->getRepository(Deck::class)->find($deckId);
        $cards = $deck->getDeckCard();
        foreach($cards as $card){
            $card->removeDeckCard($deck);
            //dd($card->getDecks());
            if ($card->getDecks()->isEmpty()){
                $entityManager->remove($card);
            }
        }
        $entityManager->remove($deck);
        $entityManager->flush();
        return $this->redirectToRoute('app_deckUser');
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
