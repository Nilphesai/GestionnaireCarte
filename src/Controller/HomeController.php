<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\CategoryRepository;
use App\Repository\DeckCardRepository;
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
    public function index(DeckRepository $deckRepository,TopicRepository $topicRepository, DeckCardRepository $deckCardReposirory, CardRepository $cardRepository,Request $request): Response
    {
        $decks = $deckRepository->findDecks();
        $topics = $topicRepository->findTopics();
        $idCards = $deckCardReposirory->findTopDeckCardsbyQttInAllDeck();
        $i = 0;
        if($idCards){
            foreach($idCards as $idCard){
                //dd($idCard['id']);
                $cards[$i] = $cardRepository->findCardById($idCard['id']);
                $i++;
            }
        }
        else{
            return $this->redirectToRoute('cards_list');
        }
        //dd($cards);
        return $this->render('home/index.html.twig', [
            'decks' => $decks,
            'topics' => $topics,
            'cards' => $cards,
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
