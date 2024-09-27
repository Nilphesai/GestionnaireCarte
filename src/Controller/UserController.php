<?php

namespace App\Controller;

use App\Repository\DeckRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    
    #[Route('/user/deck', name: 'app_deckUser')]
    public function deckUser(DeckRepository $deckRepository,Request $request): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $decks = $deckRepository->findDecksByUser($userId);

        return $this->render('user/deckUser.html.twig', [
            'decks' => $decks,
        ]);
    }
}
