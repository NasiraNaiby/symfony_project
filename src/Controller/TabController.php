<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TabController extends AbstractController
{
    #[Route('/tab/{nb?5<\d+>}', name: 'app_tab')]
    public function index($nb = 5): Response
    {
        $notes = [];
        for ($i = 0; $i < $nb; $i++) {
            $notes[] = rand(0, 20);
        }
        return $this->render('tab/index.html.twig', [
            'notes' => $notes,
        ]);
    }
}