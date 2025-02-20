<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user')]
    public function index(): Response
    {
       
        $users = [
            ['firstname' => 'Nasira', 'lastname' => 'Naeibi', 'age' => 29],
            ['firstname' => 'Raziq', 'lastname' => 'Nazari', 'age' => 31],
            ['firstname' => 'Hannah', 'lastname' => 'Nazari', 'age' => 6],
            ['firstname' => 'Ali Adil', 'lastname' => 'Nazari', 'age' => 2]
        ];
        return $this->render('user/index.html.twig', [
            'users' =>$users
                    
        ]);
    }
       
  
}
