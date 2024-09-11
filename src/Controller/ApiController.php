<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Entity\Picture;
use App\Form\SearchCardType;
use App\HttpClient\ApiHttpClient;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/cards/fetch-cards', name: 'card_fetch')]
    public function listCard(EntityManagerInterface $entityManager,Card $card = null, ApiHttpClient $apiHttpClient, Request $request){
        if(!$card){
            $card = new Card();
        }
        
        $form = $this->createForm(SearchCardType::class,$card);

        $test = $request->toArray();
        
        if($test['cardName']){
            $cards = $apiHttpClient->getCardsByFilter($test['cardName']);
            //dd($cards);
            //traitement de l'image
            foreach ($cards['data'] as $detail){
                $this->addImage($detail, $entityManager);
            }
            
            return new JsonResponse([
                'content' => $this->renderView('card/_content.html.twig', ['cards' => $cards])
            ]);
        }
        else{
            
            $cards = $apiHttpClient->getCards();
            return new JsonResponse($cards);
            return $this->render('card/_content.html.twig', [
                'formSearchCard' => $form,
                'cards' => $cards,
            ]);
        }
    }

    #[Route('/cards/next_page', name: 'next_page')]
    public function nextPage(EntityManagerInterface $entityManager,Card $card = null, ApiHttpClient $apiHttpClient, Request $request){

        $test = $request->toArray();
        
        if($test['cardName']){
            $cards = $apiHttpClient->getCardsByUrl($test['cardName']);
            //dd($cards);
            //traitement de l'image
            foreach ($cards['data'] as $detail){
                $this->addImage($detail, $entityManager);
            }
            
            return new JsonResponse([
                'content' => $this->renderView('card/_content.html.twig', ['cards' => $cards])
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
            ]);
        }
    
    }


    #[Route('/cards/add-card', name: 'card_add', methods: 'POST')]
    public function addCard(EntityManagerInterface $entityManager, Request $request, Card $card = null){
        $card = new Card();
        
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
        
        if ($name && $race && $effect && $picture){
            $card->setAttribute($attribute);
            $card->setTypecard($typecard);
            $card->setName($name);
            $card->setRace($race);
            $card->setEffect($effect);
            $card->setPicture($picture);
            $card->setRefCard($refCard);
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
            return $this->redirectToRoute('app_deck');
        } else{
            return $this->redirectToRoute('app_deck');

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
