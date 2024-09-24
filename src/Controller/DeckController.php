<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Deck;
use App\Form\DeckType;
use App\Form\SearchCardType;
use App\HttpClient\ApiHttpClient;
use App\Repository\CardRepository;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
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
        
        $cardcheck = $cardRepository->findCardByRefCard($refCard);
        if ($cardcheck){
            $deckId = $request->attributes->get('idDeck');
            $deck = $entityManager->getRepository(Deck::class)->find($deckId);
            
            $cardcheck[0]->addDeck($deck);
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
        
            $card->addDeck($deck);
            $entityManager->flush();
    
            return $this->redirectToRoute('update_deck', ['id' => $deckId]);
        }
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
        //dd($formDeck);
        //dd($_POST);
        //dd($request);
        $dec = $request->request->all() ;
        $picture = $dec['deck']['picture'];
        $formDeck->handleRequest($request);
        dd($formDeck);
        $formDeck['modelData']['picture'];
        dd($formDeck);
        if($formDeck->isSubmitted() && $formDeck->isValid()){
            
            $deck = $formDeck->getData();
            $deck->getPicture($picture);
            $entityManager->persist($deck);
            $entityManager->flush();

            return $this->redirectToRoute('app_deck');
        }
        $card = new Card();
        $form = $this->createForm(SearchCardType::class,$card);

        $cards = $apiHttpClient->getCards();
        $formDeck = $this->createForm(DeckType::class,$deck);
        $formDeck->handleRequest($request);

        return $this->render('deck/new.html.twig', [
            'formSearchCard' => $form,
            'cards' => $cards,
            'formDeck' => $formDeck,
            'deck' => $deck,
        ]);
    }

    
}
