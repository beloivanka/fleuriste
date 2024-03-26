<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\QuantityType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product', methods:['GET'])]
    public function loadAllProducts(ProductRepository $productRepository,
    PaginatorInterface $paginator,Request $request): Response
    {
        $products = $paginator->paginate(
            $productRepository->findAll(),
            $request->query->getInt('page', 1), //nombre de pages
            6 //limite par page
        );

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET', 'POST'])]
    public function showProductDetails(Product $product,SessionInterface $session, Request $request): Response
    {

        //On recupere l'id du produit
        $id = $product->getId();
        $stock = $product->getStock();

        //On recupere le panier existant
        $cart = $session->get('cart', []);

        // On ajoute le produit dans le panier s'il n'y est pas encore, sinon on incremente sa quantite

        $form = $this->createForm(QuantityType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $quantity = $form->get('quantity')->getData();
            if(empty($cart[$id]) && $quantity <= $stock){
                $cart[$id] = $form->get('quantity')->getData();

                $session->set('cart', $cart);
                //On redirige vers la page du panier
                return $this->redirectToRoute('cart_index');

            }else if (!empty($cart[$id]) && $quantity <= ($stock - $cart[$id])){
                $cart[$id] += $form->get('quantity')->getData();

                $session->set('cart', $cart);
                //On redirige vers la page du panier
                return $this->redirectToRoute('cart_index');

            }else{
                $this->addFlash('danger', 'Stock insuffisant: impossible d\'ajouter cette quantité au panier vous avez déjà ce produit dans votre panier');
            }

        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' =>$form
        ]);
    }

}
