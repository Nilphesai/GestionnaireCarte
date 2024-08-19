<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/cards', name: 'cards_list')]
    public function index(ApiHttpClient $apiHttpClient): Response
    {
        $cards = $apiHttpClient->getCards();
        return $this->render('card/index.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/cards/add-card', name: 'card_add', methods: 'POST')]
    public function addMembre(EntityInterface $entityManager, Request $request, Card $card = null){
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
            if($type == "Monstre" ){
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

            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('cards_list');
        } else{
            return $this->redirectToRoute('cards_list');

        }
    }
}
