<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PersonneController extends AbstractController
{

    #[Route('/personne', name:'app_personne')]
    public function index(ManagerRegistry $doctrine): Response
    {
         $repository  = $doctrine->getRepository(persistentObject:Personne::class);
         $personne = $repository->findAll();
        // $entitymanager->flush();
        return $this->render('personne/index.html.twig', [
            'personne'=>$personne
        ]);
    }
    #[Route('/add', name: 'app_personne_add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        // $entitymanager = $doctrine->getManager();
        // $personne = new Personne();
        // $personne->setFirstname(firstname:'Nasira');
        // $personne->setName(name:'Naeibi');
        // $personne->setAge(age:'28');

        // $entitymanager->persist($personne); it will add the insertion operation 
        // $entitymanager->flush();
        return $this->render('personne/index.html.twig');
    }

    #[Route('/personne/{id<\d+>}', name:'app_personne_detail')]
    public function detail(ManagerRegistry $doctrine, $id): Response
    {
         $repository  = $doctrine->getRepository(persistentObject:Personne::class);
         $personne = $repository->find($id);
            if(!$personne){
                $this->addFlash(type:'error', message:"la personne $id ne exisit pas !!");
                return$this->redirectToRoute('app_personne');
            }
        return $this->render('detail.html.twig', [
            'personne'=>$personne
        ]);
    }


    #[Route('/personne/all/{page?1}/{nbre?12}', name: 'app_personne_all')]
    public function indexAll(ManagerRegistry $doctrine, int $nbre, int $page): Response
    {
        $repository = $doctrine->getRepository(persistentObject:Personne::class);
        
        // Ensure the count method is supported and used correctly
        $nbPersonne = $repository->count([]);
        $nbrepage = ceil(num:$nbPersonne / $nbre);
       // dd($nbrepage);
        $personne = $repository->findBy([], null, $nbre, ($page - 1) * $nbre);

        return $this->render('personne/index.html.twig', [
            'personne' => $personne,
            'isPaginated' => true,
            'nbPersonne' => $nbPersonne,  // Pass this variable if needed in your template
            'nbrepage' => $nbrepage,
            'page' => $page,
            'nbre' => $nbre, 
        ]);
    }
}
