<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Form\PostType;
use App\Form\TopicType;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findtopics();

        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
        ]);
    }

    #[Route('/topic/show/{topicId}', name: 'show_topic')]
    public function show(TopicRepository $topicRepository,PostRepository $postRepository, Request $request): Response
    {
        $post = new post();
        $topicId = $request->attributes->get('topicId');
        //dd($topicId);
        $topic = $topicRepository->findTopicById($topicId);
        //dd($topic);
        $posts = $postRepository->findByExampleField($topic);
        //dd($posts);

        $formPost = $this->createForm(PostType::class,$post);
        return $this->render('topic/show.html.twig', [
            'posts' => $posts,
            'formPost' => $formPost,
        ]);
    }

    #[Route('/topic/new', name: 'new_topic')]
    //#[Route('/category/{id}/edit', name: 'update_topic')]
    public function new(EntityManagerInterface $entityManager, Request $request, Topic $topic = null): Response
    {
        if($topic == null){
            $topic = new topic();
        }
        
        $formTopic = $this->createForm(TopicType::class,$topic);
        $formTopic->handleRequest($request);
        if($formTopic->isSubmitted() && $formTopic->isValid()){
            $topic = $formTopic->getData();
            $entityManager->persist($topic);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_category');
        }
       

        return $this->render('topic/new.html.twig', [
            'formTopic' => $formTopic,
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
