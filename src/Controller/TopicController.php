<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {

        return $this->render('topic/index.html.twig', [
            'controller_name' => 'topicController',
        ]);
    }

    #[Route('/topic/{topicId}', name: 'show_topic')]
    public function show(TopicRepository $topicRepository, Request $request): Response
    {
        $topicId = $request->attributes->get('topicId');
        //dd($topicId);
        $topic = $topicRepository->findTopicById($topicId);
        dd($topic[0]);
        return $this->render('topic/show.html.twig', [
            'topic' => $topic[0],
        ]);
    }

    #[Route('/topic/new', name: 'new_topic')]
    #[Route('/category/{id}/edit', name: 'update_category')]
    public function new(EntityManagerInterface $entityManager, Request $request, Topic $topic = null): Response
    {
        if($topic == null){
            $topic = new topic();
        }
        
        $formTopic = $this->createForm(TopicType::class,$topic);
        $formTopic->handleRequest($request);
        if($formTopic->isSubmitted() && $formTopic->isValid()){
            $topic = $formTopic->getData();
        }
        $entityManager->persist($topic);
        $entityManager->flush();

        return $this->redirectToRoute('app_topic');

        return $this->render('topic/new.html.twig', [
            'formTopic' => $formTopic,
            'edit' => $formTopic->getId(),
        ]); 
        
    }

    #[Route('/topic/{topicId}/delete', name: 'delete_topic')]
    public function deleteTopic(EntityManagerInterface $entityManager, Request $request,Topic $topic = null): Response
    {
        $topicId = $request->attributes->get('topicId');
        $topic = $entityManager->getRepository(Topic::class)->find($topicId);
        $posts = $topic->getPosts();
        foreach($posts as $post){
            $post->removeTopic($topic);
            if ($post->getPosts()->isEmpty()){
                $entityManager->remove($post);
            }
        }
        $entityManager->remove($topic);
        $entityManager->flush();
        return $this->redirectToRoute('app_category');
    }
}