<?php

namespace App\Controller;

use App\Entity\Logo;
use App\Repository\LogoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogoController extends AbstractController
{
    public function logo(LogoRepository $logoRepository): Response
    {
        return $this->render('partials/_logo.html.twig', [
            'logo' => $logoRepository->findAll()
        ]);
    }

}