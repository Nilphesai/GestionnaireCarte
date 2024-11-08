<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findTopics();

        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
        ]);
    }
}
