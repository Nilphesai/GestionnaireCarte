<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Entity\Picture;
use App\Form\SearchCardType;
use App\HttpClient\ApiHttpClient;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/cards', name: 'cards_list')]
    public function index(EntityManagerInterface $entityManager, Card $card = null, ApiHttpClient $apiHttpClient): Response
    {
        
        $card = new Card();
        $formSearchCard = $this->createForm(SearchCardType::class,$card);
        $formAddCard = $this->createForm(CardType::class,$card);
        $cards = $apiHttpClient->getCards();
        
        //traitement de l'image
        
        foreach ($cards['data'] as $detail){
            $this->addImage($detail, $entityManager);
        }
          
        
        return $this->render('card/index.html.twig', [
            'formSearchCard' => $formSearchCard,
            'cards' => $cards,
            'formAddCard' => $formAddCard,
        ]);
        
    }

    #[Route('/cards/fetch-cards', name: 'card_fetch', methods: ['POST'])]
    public function listCard(DeckRepository $deckRepository, EntityManagerInterface $entityManager,Card $card = null, ApiHttpClient $apiHttpClient, Request $request, SessionInterface $session){
        
        if(!$card){
            $card = new Card();
        }
        $session->set('isDeck', true);
        $form = $this->createForm(SearchCardType::class,$card);
        
        $test = $request->toArray();
        
        $parent = $request->headers->get('referer');
        if ($parent && strpos($parent,'deck') !== false){
            $isDeck = true;
            $pattern = '/deck\/(.*?)\/edit/';
            $matches = [];
            
            if ($parent && preg_match($pattern, $parent, $matches)) {
                // Si une correspondance est trouvée, $matches[1] contiendra la partie capturée
                $idDeck = $matches[1];
                
            }
        }
        else{
            $isDeck = false;
        }
        //dd($isDeck);
        if($test['cardName']){
            
            //dd($test['cardName']);
            $cards = $apiHttpClient->getCardsByFilter($test['cardName']);
            //dd($test['cardName'][1]);
            if(str_contains($test['cardName'][1],"Effect Monster")){
                
                foreach($cards['data'] as $key => $card){
                    //dd($card);
                    if($card['type'] === 'Spell Card' || $card['type'] === 'Trap Card'){
                        
                        unset($cards['data'][$key]);
                        //dd($card);
                    }
                }
            }
            //dd($cards);
            //traitement de l'image
            foreach ($cards['data'] as $detail){
                $this->addImage($detail, $entityManager);
            }

            //si on est dans editDeck
            if($isDeck){
                
                $decks = $deckRepository->findDecksById($idDeck);
                //dd($decks);
                return new JsonResponse([
                    'content' => $this->renderView('card/_content.html.twig', [
                        'cards' => $cards,
                        'deck' => $decks[0],
                        'isDeck' => $isDeck,])
                ]);
            }

            return new JsonResponse([
                'content' => $this->renderView('card/_content.html.twig', [
                    'cards' => $cards,
                    'isDeck' => $isDeck,])
            ]);
        }
        else{
            
            $cards = $apiHttpClient->getCards();
            return new JsonResponse($cards);
            return $this->render('card/_content.html.twig', [
                'formSearchCard' => $form,
                'cards' => $cards,
                'isDeck' => $isDeck,
            ]);
        }
    }

    #[Route('/cards/next_page', name: 'next_page')]
    public function nextPage(DeckRepository $deckRepository, EntityManagerInterface $entityManager,Card $card = null, ApiHttpClient $apiHttpClient, Request $request){
        
        $test = $request->toArray();
        $parent = $request->headers->get('referer');
        if ($parent && strpos($parent,'deck') !== false){
            $isDeck = true;
            $pattern = '/deck\/(.*?)\/edit/';
            $matches = [];
            if ($parent && preg_match($pattern, $parent, $matches)) {
                // Si une correspondance est trouvée, $matches[1] contiendra la partie capturée
                $idDeck = $matches[1];
                
            }
        }
        else{
            $isDeck = false;
        }
        
        if($test['cardName']){
            $cards = $apiHttpClient->getCardsByUrl($test['cardName']);
            //dd($cards);
            //traitement de l'image
            foreach ($cards['data'] as $detail){
                $this->addImage($detail, $entityManager);
            }
            if($isDeck){
                
                $decks = $deckRepository->findDecksById($idDeck);
                //dd($decks);
                return new JsonResponse([
                    'content' => $this->renderView('card/_content.html.twig', [
                        'cards' => $cards,
                        'deck' => $decks[0],
                        'isDeck' => $isDeck,])
                ]);
            }
            return new JsonResponse([
                'content' => $this->renderView('card/_content.html.twig', [
                    'cards' => $cards,
                    'isDeck' => $isDeck,
                    ])
            ]);
        }
        else{
            if(!$card){
                $card = new Card();
            }
            
            $form = $this->createForm(SearchCardType::class,$card);
    
            $cards = $apiHttpClient->getCards();
            return new JsonResponse($cards);
            return $this->render('card/_content.html.twig', [
                'formSearchCard' => $form,
                'cards' => $cards,
                'isDeck' => $isDeck,
            ]);
        }
    
    }

    private function addImage(array $detail, EntityManagerInterface $entityManager) {
        $route = $detail['card_images'][0]['image_url_small'];
        $imageContent = file_get_contents($route);
        $imagePath = '../public/images/' . $detail['id'] . '.jpg';
        if (!file_exists($imagePath)) {
            file_put_contents($imagePath, $imageContent);
            $picture = new Picture();
            $picture->setRefCard($detail['id']);
            $picture->setRoute($imagePath);
            $entityManager->persist($picture);
            $entityManager->flush();
        }
    }

    #[Route('/cards/{id}', name: 'show_card')]
    public function show(string $id, ApiHttpClient $apiHttpClient): Response
    {
        
        $card = $apiHttpClient->getCardsById($id);

        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }
}
