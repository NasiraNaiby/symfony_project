<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TwigHeritageController extends AbstractController
{
    #[Route('/twig/heritage', name: 'app_twig_heritage')]
    public function index(): Response
    {
        return $this->render('twig_heritage/index.html.twig', [
            'controller_name' => 'TwigHeritageController',
        ]);
    }

    #[Route('/twig/mypage', name: 'app_twig_heritage_mypage')]
    public function heritage(): Response
    {
        return $this->render(view:'heritage.html.twig');
    }
}
