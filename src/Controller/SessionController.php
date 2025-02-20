<?php

namespace App\Controller;

use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session = $request ->getSession(); // here the getSession() method does the same action as in php session_start()
        if($session->has(name:'nbVisite')){
            $nbrVisite = $session->get(name:'nbVisite')+1;
        }
        else{
            $nbrVisite = 1;
        }
        $session->set('nbVisite',$nbrVisite);
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
    // #[Route('/multi/{entree1}/{entree2}')]
    #[Route('/multi/{entree1}/{entree2}',
    name:'multiplication',
    requirements:['entree1'=>'\d+','entree2'=>'\d+',]
    )]
    public function multiplication($entree1, $entree2)
    {
        $result = $entree1 * $entree2;

        return new Response(content:"<h1>$result</h1>");
    }
}
