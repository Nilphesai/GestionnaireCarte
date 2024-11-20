<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findCategories();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{id}', name: 'show_category')]
    public function show(CategoryRepository $categoryRepository, Request $request): Response
    {
        $categoryId = $request->attributes->get('id');
        $category = $categoryRepository->findCategoryById($categoryId);
        //dd($category[0]);
        return $this->render('category/show.html.twig', [
            'category' => $category[0],
        ]);
    }

    #[Route('/category/new', name: 'new_category')]
    #[Route('/category/{id}/edit', name: 'update_category')]
    public function new(EntityManagerInterface $entityManager, Request $request, Category $category = null): Response
    {
        if($category == null){
            $category = new category();
        }
        
        $formCategory = $this->createForm(CategoryType::class,$category);
        $formCategory->handleRequest($request);
        if($formCategory->isSubmitted() && $formCategory->isValid()){
            $category = $formCategory->getData();
        }
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_category');

        return $this->render('category/new.html.twig', [
            'formCategory' => $formCategory,
            'edit' => $formCategory->getId(),
        ]);  
        
    }

    #[Route('/category/{categoryId}/delete', name: 'delete_Category')]
    public function deleteCategory(EntityManagerInterface $entityManager, Request $request,Category $category = null): Response
    {
        $categoryId = $request->attributes->get('categoryId');
        $category = $entityManager->getRepository(Category::class)->find($categoryId);
        $topics = $category->getTopics();
        foreach($topics as $topic){
            $topic->removeCategory($category);
            if ($topic->getCategory()->isEmpty()){
                $entityManager->remove($topic);
            }
        }
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_category');
    }
}
