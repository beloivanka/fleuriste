<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\AddressType;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order', name: 'app_order_')]
class OrderController extends AbstractController
{
    #[Route('/details', name: 'details')]
    public function validateOrder(Request $request,
    SessionInterface $session, ProductRepository $productRepository,
    EntityManagerInterface $em): Response{
        // On récupère le panier
        $cart = $session->get('cart', []);
        // On vérifie si le panier est vide
        if($cart === []){
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('main');
        }

        //On initialise des variables pour afficher le récapulatif de la commande
        $data = [];
        $total = 0;
        // On parcourt le panier et on multiplie le prix de chaque produit par sa quantité
        foreach($cart as $id => $quantity){
            $product = $productRepository->find($id);
            $data[]=[
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        //On récupère l'adresse favorie de l'utilisateur

        $userFavAddress = $this->getUser()->getFavAddress();
        $userFavZipcode = $this->getUser()->getFavZipcode();
        $userFavCity = $this->getUser()->getFavCity();

        $form = $this->createForm(AddressType::class, [
            'address' => $userFavAddress,
            'zipcode' => $userFavZipcode,
            'city' => $userFavCity
        ]);
        

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

        //On sauvegarde le mode de livraison favori 
        if($form->get('checkbox')->getData() === true){
            $user = $this->getUser();
            $user->setFavAddress($form->get('address')->getData());
            $user->setFavZipcode($form->get('zipcode')->getData());
            $user->setFavCity($form->get('city')->getData());
        }

        //Le panier n'est pas vide, on crée la commande
        $order = new Order();

        // On remplit la commande
        $order->setUser($this->getUser());
        $order->setFirstName($form->get('firstName')->getData());
        $order->setLastName($form->get('lastName')->getData());
        $order->setAddress($form->get('address')->getData());
        $order->setZipcode($form->get('zipcode')->getData());
        $order->setCity($form->get('city')->getData());
        $order->setTotal($total);
        
        // On parcourt le panier pour créer les détails de commande
        foreach ($cart as $item => $quantity){
            $orderDetails = new OrderDetails();

            //On va chercher le produit
            $product = $productRepository->find($item);
            $price = $product->getPrice();

            // On met à jour le stock des produits

                $data[]=[
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $productStock = $product->getStock();
                $newStock = $productStock - $quantity;
                $product->setStock($newStock);

            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            // On ajoute
            $order->addOrderDetail($orderDetails);
        }

        //On vérifie si l'utilisateur a activé son compte, sinon il peut pas valider la commande
        $user = $this->getUser();
        if($user->getIsVerified()){
            $em->persist($order);
            $em->persist($product);
            $em->flush();
    
            $session->remove('cart');
    
            $this->addFlash('success', 'Commande créée avec succès');
    
            return $this->redirectToRoute('main');
        }else{
            $this->addFlash('danger', 'Veuillez activer votre compte pour continuer vos achats');
        }
    }

        return $this->render('cart/orderDetails.html.twig', [
            'data' => $data,
            'total'=>$total,
            'form' => $form,
        ]);
    }
}
