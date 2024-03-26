<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, 
    ProductRepository $productRepository): Response
    {

        $FavoriteProducts = $productRepository->findProductsByCategory();

        return $this->render('main/index.html.twig', [
            'favProducts' => $FavoriteProducts
        ]);
    }

}
