<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/personne/all/age/{ageMin}/{ageMax}', name:'app_personne_age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
         $repository  = $doctrine->getRepository(persistentObject:Personne::class);
         $personne = $repository->findPersonneByAgeInterval($ageMin, $ageMax);
        // $entitymanager->flush();
        return $this->render('personne/index.html.twig', [
            'personne'=>$personne
            
        ]);
    }
    #[Route('/personne/state/age/{ageMin}/{ageMax}', name:'app_personne_age_state')]
    public function statesPersonneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $state = $repository->statesPersonneByAgeInterval($ageMin, $ageMax);
    
        $ageMoyen = $state[0]['ageMoyen'] ?? 'N/A';
        $nombrePerson = $state[0]['nombrePerson'] ?? 'N/A';
    
        return $this->render('state.html.twig', [
            'ageMoyen' => $ageMoyen,
            'nombrePerson' => $nombrePerson,
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ]);
    }
    
    
    #[Route('/personne/add', name: 'app_personne_add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request): Response
    {
        
        $Personne = new Personne();
        $form = $this->createForm(PersonneType::class, $Personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        $form->handleRequest($request);    //it will take all the data send by the form make ready for submission
        if($form->isSubmitted()){
            $entitymanager = $doctrine->getManager();
            $entitymanager->persist($Personne);
            $entitymanager->flush();
            $this->addFlash("succes", "the person is added ");
            return $this->redirectToRoute(route:'app_personne_all');
        }else{
            $this->addFlash("error", "the person is not  added ");
        }
        return $this->render('personne/add.html.twig',['form'=>$form->createView()]);
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

    #[Route('/personne/delete/{id}', name: 'app_personne_delete')]
    public function delete(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse
    {
        //recuperér la personne 
        if($personne){
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash(type:'success', message:'La personne a été suprime!!');
        } else {
            $this->addFlash(type:'error', message:'La personne ne existe pas !!');
        }
        return $this->redirectToRoute(route:'app_personne_all');
    }


    #[Route('/personne/update/{id}/{name}/{firstname}/{age}', name: 'app_personne_update')]
    public function updatePersonne(Personne $personne = null, ManagerRegistry $doctrine, $name, $firstname, $age): RedirectResponse
    {
        //recuperér la personne 
        if($personne){
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash(type:'success', message:'La personne a été mise a jour!!');
        } else {
            $this->addFlash(type:'error', message:'La personne ne existe pas !!');
        }
        return $this->redirectToRoute(route:'app_personne_all');
    }
    
    
}
