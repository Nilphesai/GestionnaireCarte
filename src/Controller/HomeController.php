<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DeckRepository;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(DeckRepository $deckRepository,TopicRepository $topicRepository,Request $request): Response
    {
        $decks = $deckRepository->findDecks();
        $topic = $topicRepository->findTopics();
        return $this->render('home/index.html.twig', [
            'decks' => $decks,
            'topics' => $topic,
        ]);
    }

    #[Route('/home/adminPanel', name: 'app_AdminPanel')]
    public function AdminPanel(DeckRepository $deckRepository,CategoryRepository $categoryRepository, TopicRepository $topicRepository, PostRepository $postRepository, UserRepository $userRepository ): Response
    {
        $decks = $deckRepository->findAll();
        $categories = $categoryRepository->findAll();
        $topics = $topicRepository->findAll();
        $posts = $postRepository->findAll();
        return $this->render('home/adminPanel.html.twig', [
            'decks' => $decks,
            'categories' => $categories,
            'topics' => $topics,
            'posts' => $posts
            
        ]);
    }
}
