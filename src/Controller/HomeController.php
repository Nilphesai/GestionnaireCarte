<?php

namespace App\Controller;

use App\Repository\DeckRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(DeckRepository $deckRepository,Request $request): Response
    {
        $decks = $deckRepository->findDecks();

        return $this->render('home/index.html.twig', [
            'decks' => $decks,
        ]);
    }
}
