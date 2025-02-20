<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FirstController extends AbstractController
{
    #[Route('/first/{name}/{lastname}', name: 'app_first')]
    public function index(Request $request, $name, $lastname): Response
    {
        return $this->render('first/index.html.twig', [
            'name' =>$name,
            'lastname' => $lastname,
            'path' => '                   '
        ]);
    }

    #[Route('/template', name: 'app_first_template')]
    public function template(): Response
    {
        return $this->render(view:'template.html.twig');
    }

  
}
