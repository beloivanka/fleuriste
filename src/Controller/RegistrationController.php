<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\JWTService;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager,
    SendMailService $mail, JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        //  On traite les données soumises dans la requête HTTP pour peupler le formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On encode le plain password
            $user->setPassword(
                // On définit le mot de passe de l'utilisateur en le hashant à l'aide du service
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
                );
            // On persiste l'utilisateur nouvellement créé dans la base de données
            $entityManager->persist($user);
            // On enregistre les modifications dans la base de données
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte a été créé');

            //on génère le JWT de l'utilisateur
            //on crée le Header
            $header=[
                'typ'=>'JWT',
                'alg'=>'HS256'
            ];
            //on crée le Payload
            $payload = [
                'user_id'=>$user->getId()
            ];
            //on génère le token
            $token = $jwt->generate($header, $payload,
            $this->getParameter('app.jwtsecret'));
            //On envoie un mail
            $mail->send(
                'sendtest@laposte.net',
                $user->getEmail(),
                'Activation de votre compte sur Fleurs de Nadine', //titre du mail
                'register', //template
                [
                    //le tableau associatif qui contient les données à passer au template d'e-mail
                    'user'=>$user,
                    'token'=>$token
                ]
            );
            // On authentifie l'utilisateur nouvellement enregistré
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt,
    UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        //dd($jwt->check($token, $this->getParameter('app.jwtsecret')));

        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && 
        $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            //On recupere le Payload
            $payload = $jwt->getPayload($token);

            //On récupère le user dans le token
            $user = $userRepository->find($payload['user_id']);

            //on vérifie que l'utilisateur existe et n'a pas encore activé son compte

            if($user && !$user->getIsVerified()){
                $user->setIsVerified(true);
                $em->flush();
                $this->addFlash('success', 'Votre compte a été validé');
                return $this->redirectToRoute('profile_index');
            }
        }
        //Ici un problème se pose dans le token
        $this->addFlash('danger', 'Ce token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/resendverif', name: 'resend_verif')]

    public function resendVerif(JWTService $jwt, SendMailService $mail,
    UserRepository $userRepository): Response{
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('danger', 'Vous devez vous connecter pour avoir accès à cette page');
            return $this->redirectToRoute('app_login');
        }

        if($user->getIsVerified()){
            $this->addFlash('warning', 'Vous avez déjà activé votre compte');
            return $this->redirectToRoute('profile_index');
        }

        //on génère le JWT de l'utilisateur
            //on crée le Header
            $header=[
                'typ'=>'JWT',
                'alg'=>'HS256'
            ];
            //on crée le Payload

            $payload = [
                'user_id'=>$user->getId()
            ];

            //on génère le token
            $token = $jwt->generate($header, $payload,
            $this->getParameter('app.jwtsecret'));

            //dd($token); pour verifier

            //On envoie un mail
            $mail->send(
                'sendtest@laposte.net',
                $user->getEmail(),
                'Activation de votre compte sur Fleurs de Nadine',
                'register',
                [
                    'user'=>$user,
                    'token'=>$token
                ]
            );

            $this->addFlash('success', 'Le mail de vérification a été envoyé');
            return $this->redirectToRoute('profile_index');

    }
}

