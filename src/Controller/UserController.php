<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/user/{id}', name: 'show_user')]
    public function show(UserRepository $userRepository, Request $request): Response
    {
        $userId = $request->attributes->get('id');
        $user = $userRepository->findUserById($userId);
        
        return $this->render('user/show.html.twig', [
            'user' => $user[0],
        ]);
    }

    #[Route('/user/new', name: 'new_user')]
    #[Route('/user/edit/{id}', name: 'update_user')]
    public function new(EntityManagerInterface $entityManager, Request $request, User $user = null): Response
    {
        if($user == null){
            $user = new user();
        }
        //dd($request);
        $formUser = $this->createForm(UserType::class,$user);
        $formUser->handleRequest($request);
        if($formUser->isSubmitted() && $formUser->isValid()){
            $user = $formUser->getData();
            $entityManager->persist($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user');
        }
        dd($formUser);

        return $this->render('user/new.html.twig', [
            'formUser' => $formUser,
        ]); 
        
    }




    #[Route('/user/username', name: 'update_username', methods: 'POST')]
    public function changeUsername(EntityManagerInterface $entityManager){
        
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($username){
            $user = $this->getUser();
            $userId = $user->getId();
            $user = $entityManager->getRepository(User::class)->find($userId);
            $user->setUsername($username);
                
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
    
        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/email', name: 'update_email', methods: 'POST')]
    public function changeEmail(EntityManagerInterface $entityManager){
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($email){
            $user = $this->getUser();
            $userId = $user->getId();
            $user = $entityManager->getRepository(User::class)->find($userId);
            $user->setEmail($email);
                
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
    
        return $this->redirectToRoute('app_user');
    }
}
