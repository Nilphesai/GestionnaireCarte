<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post/{topicId}', name: 'app_post')]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $topicId = $request->attributes->get('topicId');

        $posts = $postRepository->findPostsByTopic($topicId);
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
        

    }

    #[Route('/post/{postId}', name: 'show_post')]
    public function show(PostRepository $postRepository, Request $request): Response
    {
        $postId = $request->attributes->get('postId');

        $post = $postRepository->findPosts($postId);
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
        

    }
    #[Route('/post/new', name: 'new_post')]
    #[Route('/post/new/{id}', name: 'deck_post')]
    #[Route('/post/{id}/edit', name: 'update_post')]
    public function new(EntityManagerInterface $entityManager, Request $request, Post $post = null): Response
    {
        if($post == null){
            $post = new post();
        }
        
        $formPost = $this->createForm(PostType::class,$post);
        $formPost->handleRequest($request);
        if($formPost->isSubmitted() && $formPost->isValid()){
            $post = $formPost->getData();
        }
        $entityManager->persist($post);
        $entityManager->flush();
        $deckId = $request->attributes->get('id');
        //dd($deckId);
        return $this->redirectToRoute('show_deck', [
            'id' => $deckId,
        ]);
        return $this->render('post/new.html.twig', [
            'formPost' => $formPost,
            'edit' => $formPost->getId(),
        ]); 
        
    }

    #[Route('/topic/{postId}/delete', name: 'delete_post')]
    public function deletePost(EntityManagerInterface $entityManager, Request $request,Post $post = null): Response
    {
        $postId = $request->attributes->get('postId');
        $post = $entityManager->getRepository(Post::class)->find($postId);
        
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('app_category');
    }
}
