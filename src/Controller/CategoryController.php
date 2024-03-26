<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    // #[Route('/category', name: 'app_category', methods: ['GET'])]
    // public function loadAll(CategoryRepository $categoryRepository): Response
    // {
    //     return $this->render('category/index.html.twig', [
    //         'categories' => $categoryRepository->findAll(),
    //     ]);
    // }

        public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('partials/_categories.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show', methods: ['GET'])]
    public function loadProductsByCategory(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
