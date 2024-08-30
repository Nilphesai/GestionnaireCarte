<?php

namespace App\Controller;

use App\Entity\Card;
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

    #[Route('/deck/add-to-deck', name: 'card_add_to_deck', methods: 'POST')]
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
        $link_marker = filter_input(INPUT_POST, 'link_markrt', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       
        if ($name && $race && $effect && $picture){
            $card->setName($name);
            $card->setRace($race);
            $card->setEffect($effect);
            $card->setPicture($picture);
            if($type == "effect Monstre" ){
                $card->setRace($race);
                $card->setAtt($att);
                if ($type =="Normal" ||$type =="Effect"||$type=="fusion"||$type=="synchro"||$type=="Xyz"){
                    $card->setLevel($level);
                    $card->setDef($def);
                }
                elseif($type=="Pendule"){
                    $card->setLevel($level);
                    $card->setDef($def);
                    $card->setScale($scale);
                }
                elseif($type=="Link"){
                    $card->setLink($link);
                    $card->setLink_marker($link_marker);
                }
            }

            $entityManager->persist($card);
            $entityManager->flush();

            return $this->render('deck/_content.html.twig', [
                'formSearchCard' => $form,
                'cards' => $cards,
            ]);
        } else{
            return $this->redirectToRoute('app_deck');

        }
    }
}
