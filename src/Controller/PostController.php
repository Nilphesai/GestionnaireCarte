<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/new/{idDeck}', name: 'new_post')]
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
        $deckId = $request->attributes->get('idDeck');
        //dd($deckId);
        return $this->redirectToRoute('show_deck', [
            'id' => $deckId,
        ]);
        
    }
}
