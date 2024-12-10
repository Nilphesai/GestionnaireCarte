<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\DeckRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    
    #[Route('/user/deck/{id}', name: 'app_deckUser')]
    public function deckUser(DeckRepository $deckRepository,Request $request): Response
    {
        $userId = $request->attributes->get('id');
        $decks = $deckRepository->findDecksByUser($userId);

        return $this->render('user/deckUser.html.twig', [
            'decks' => $decks,
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

        return $this->render('user/new.html.twig', [
            'formUser' => $formUser,
        ]); 
        
    }


    #[Route('/user/username', name: 'update_username', methods: 'POST')]
    public function changeUsername(EntityManagerInterface $entityManager, Request $request,CsrfTokenManagerInterface $csrfTokenManager){
        
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$request->getSession()->isStarted()) {
    $request->getSession()->start();
}

        $token = new CsrfToken('unique_identifier', $request->request->get('_token'));

            if (!$csrfTokenManager->isTokenValid($token)) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }
        
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
    public function changeEmail(EntityManagerInterface $entityManager, Request $request,CsrfTokenManagerInterface $csrfTokenManager){
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        

        $token = new CsrfToken('unique_identifier', $request->request->get('_token'));

        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }
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

    #[Route('/user/password', name: 'update_password', methods: 'POST')]
    public function changePassword(EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher, Request $request,CsrfTokenManagerInterface $csrfTokenManager){
        
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $token = new CsrfToken('unique_identifier', $request->request->get('_token'));
        
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }
        if ($password){
            $user = $this->getUser();
            $userId = $user->getId();
            $user = $entityManager->getRepository(User::class)->find($userId);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
                
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
    
        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/delete/{userId}', name: 'delete_user')]
    public function deleteUser(EntityManagerInterface $entityManager, Request $request,User $user = null): Response
    {
        
        $userId = $request->attributes->get('userId');
        $user = $entityManager->getRepository(User::class)->find($userId);
        
        $decks = $user->getDecks();
        $posts = $user->getPosts();
        $topics = $user->getTopics();

        if ($posts) {
            foreach($posts as $post){
                $entityManager->remove($post);
            }
        }


        if ($topics) {
            foreach($topics as $topic){
                $entityManager->remove($topic);
                
            }
        }


        if ($decks) {
            foreach($decks as $deck){
                $entityManager->remove($deck);
            }
        }

        

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
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
}
