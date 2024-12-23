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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    #[Route('/user/avatar', name: 'update_avatar', methods: 'POST')]
    public function changeAvater(EntityManagerInterface $entityManager, Request $request,CsrfTokenManagerInterface $csrfTokenManager){
        
            // Vérifier si un fichier a été téléchargé
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $fileName = $_FILES['image']['name'];
                $fileTmpName = $_FILES['image']['tmp_name'];
                $fileSize = $_FILES['image']['size'];
                $fileError = $_FILES['image']['error'];
                $fileType = $_FILES['image']['type'];
        
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
                if (in_array($fileExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 5000000) { // Limite de taille de fichier de 5MB
                            $fileNewName = uniqid('', true) . "." . $fileExt;
                            $fileDestination = 'public/assets' . $fileNewName;
        
                            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                echo "L'image a été téléchargée avec succès!";
                            } else {
                                echo "Erreur lors du téléchargement de l'image.";
                            }
                        } else {
                            echo "Le fichier est trop volumineux.";
                        }
                    } else {
                        echo "Erreur lors du téléchargement du fichier.";
                    }
                } else {
                    echo "Type de fichier non autorisé.";
                }
            } else {
                echo "Aucun fichier téléchargé.";
            }
        
        $token = new CsrfToken('unique_identifier', $request->request->get('_token'));

            if (!$csrfTokenManager->isTokenValid($token)) {
                throw new InvalidCsrfTokenException('Invalid CSRF token.');
            }

        if ($fileDestination){
            $user = $this->getUser();
            $userId = $user->getId();
            $user = $entityManager->getRepository(User::class)->find($userId);
            $user->setUsername($fileDestination);
                
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
    
        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/username', name: 'update_username', methods: 'POST')]
    public function changeUsername(EntityManagerInterface $entityManager, Request $request,CsrfTokenManagerInterface $csrfTokenManager){

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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

    public function square(UploadedFile $picture, string $folder = '', int $width = 250):string{
        //nouveaux nom à l'image
        $file = md5(uniqid(rand(), true)) . '.webp';

        return $file;
    }

}
