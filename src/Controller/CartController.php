<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    //La fonction pour afficher le contenu du panier
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SessionInterface $session, ProductRepository $productRepository){
        //On récupère le panier de l'utilisateur à partir de la session
        //S'il n'existe pas, on initialise un tableau vide
        $cart = $session->get('cart',[]);

        //On initialise des variables
        $data = [];
        $total = 0;
        // On parcourt chaque élément du panier (clé; valeur)
        foreach($cart as $id => $quantity){
            $product = $productRepository->find($id);
            // On vérifie que le produit existe dasn la BDD
            // Sinon on le supprime du panier
            if (!$product) {
                unset($cart[$id]);
                $session->set('cart', $cart);
                continue;
            }
            //On ajoute les informations sur le produit et sa quantité au tableau $data.
            $data[]=[
                'product' => $product,
                'quantity' => $quantity
            ];
            //On calcule le total des achats
            //en multipliant le prix du produit par sa quantité et en l'ajoutant au total
            $total += $product->getPrice() * $quantity;
        }
        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }

    //La fonciton qui incrémente la quantité du produit dans le panier
    #[Route('/add/{id}', name: 'add', methods: ['GET', 'POST'])]
    public function add(Product $product, SessionInterface $session){
        //On recupere l'id du produit
        $id = $product->getId();
        $stock = $product->getStock();

        //On recupere le panier existant
        $cart = $session->get('cart', []);

        // On ajoute le produit dans le panier s'il n'y est pas encore, sinon on incremente sa quantite
        if(empty($cart[$id]) && $cart[$id] <= $stock){
            $cart[$id] = 1;
        }else if($cart[$id] < $stock){
            $cart[$id]++;
        }else{
            $this->addFlash('danger', 'Stock insuffisant : impossible d\'ajouter cette quantité au panier');
        }

        $session->set('cart', $cart);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    //La fonciton qui décrémente la quantité du produit dans le panier
    #[Route('/remove/{id}', name: 'remove', methods: ['GET', 'POST'])]
    public function remove(Product $product, SessionInterface $session){
        //On recupere l'id du produit
        $id = $product->getId();

        //On recupere le panier existant
        $cart = $session->get('cart', []);

        // On retire le produit du panier s'il n'y a qu'un exemplaire, sinon on decremente sa quantite
        if(!empty($cart[$id])){
            if($cart[$id] > 1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }

        $session->set('cart', $cart);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }


    // La fonction qui supprime le produit du panier
    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Product $product, SessionInterface $session){
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    // La fonction qui vide le panier
    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session){
        $session->remove('cart');
        return $this->redirectToRoute('cart_index');
    }
}