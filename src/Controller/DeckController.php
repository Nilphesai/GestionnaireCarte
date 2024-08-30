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
}
