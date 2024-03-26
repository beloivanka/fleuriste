<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\EditUserPasswordType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profile', name: 'profile_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'User Accaunt',
        ]);
    }

    #[Route('/orders', name: 'orders', methods: 'GET')]
    public function loadUserOrders(ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        $orders = $user->getOrders();

        
        return $this->render('user/orders.html.twig', [
            'orders' => $orders
        ]);
    }


    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On vérifie si le mot de passe soumis dans le formulaire est valide en utilisant le service UserPasswordHasherInterface
            if($hasher->isPasswordValid($user, $form->get('plainPassword')->getData())){

                $user = $form->getData();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a été modifié');
                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            
            }else{
                $this->addFlash('danger', 'Le mot de passe n\'est pas correct');
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/edit/password/{id}', name: 'edit_password', methods: ['GET', 'POST'])]
    public function editUserPassword(Request $request, User $user, EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(EditUserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user, $form->get('plainPassword')->getData()))
            {
                $user->setPassword($hasher->hashPassword($user, $form->get('newPassword')->getData()));

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a été modifié');
                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('danger', 'Le mot de passe actuel n\'est pas correct');
            }
        }

        return $this->render('user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte a été supprimé');
        }

        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
    }
}
