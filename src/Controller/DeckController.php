<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Deck;
use App\Form\DeckType;
use App\Form\SearchCardType;
use App\HttpClient\ApiHttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeckController extends AbstractController
{
    #[Route('/deck', name: 'app_deck')]
    public function index(Card $card = null, ApiHttpClient $apiHttpClient): Response
    {
        $card = new Card();
        $form = $this->createForm(SearchCardType::class,$card);

        $cards = $apiHttpClient->getCards();

        return $this->render('deck/index.html.twig', [
            'formSearchCard' => $form,
            'cards' => $cards,
        ]);
    }

    #[Route('/deck/add-card', name: 'card_add_to_deck', methods: 'POST')]
    public function addCard(EntityManagerInterface $entityManager, Request $request, Card $card = null){
        
        $card = new Card();
        
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_NUMBER_INT);

        $refCard = filter_input(INPUT_POST, 'refCard', FILTER_SANITIZE_NUMBER_INT);

        //addCard
            $card->setName($name);
            $card->setRefCard($refCard);
            $entityManager->persist($card);
            $entityManager->flush();


        return $this->redirectToRoute('add_deck');
    }

    #[Route('/deck/add', name: 'add_deck')]
    public function addDeck(Card $card = null,Deck $deck = null, ApiHttpClient $apiHttpClient): Response
    {
        $card = new Card();
        $form = $this->createForm(SearchCardType::class,$card);

        $cards = $apiHttpClient->getCards();

        $deck = new deck();
        $formDeck = $this->createForm(DeckType::class,$deck);


        return $this->render('deck/new.html.twig', [
            'formSearchCard' => $form,
            'cards' => $cards,
            'formDeck' => $formDeck,
        ]);
    }

    
}
