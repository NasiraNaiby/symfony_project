<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use App\Form\PersonneType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Repository\PersonneRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Helpers;
use App\Service\MailerService;
use App\Service\PdfService;
use App\Service\UploaderService;
use Doctrine\Migrations\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class PersonneController extends AbstractController
{
    private $dispatcher;
    private $logger;

    public function __construct(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    } // here we have injeted a dependency of logger interface inside of our class

    // #[Route('/test-email', name: 'test_email')]
    // public function testEmail(MailerInterface $mailer)
    // {
    //     $email = (new Email())
    //         ->from('nasira3795@gmail.com')
    //         ->to('nasira3795@gmail.com')
    //         ->subject('Test Email')
    //         ->text('This is a test email.');

    //     $mailer->send($email);

    //     return new Response('Test email sent.');
    // }
    
    #[Route('/pdf/{id}', name:'app_personne_pdf')]
    public function generatePDFperson(Personne $personne = null, PdfService $pdf )
    {
        $html = $this->render('detail.html.twig', ['personne' => $personne]);
        $pdf->showPdfFile($html);
        
    }



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
    public function addPersonne(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger,
    #[Autowire('%kernel.project_dir%/public/uploads')] string $imagedirectory)
    {
        
        
    }


    #[Route('/personne/edit/{id?0}', name: 'app_personne_edit')]
    public function editPersonne(
        Personne $personne = null, 
        ManagerRegistry $doctrine, 
        Request $request,
        UploaderService $uploaderService,
        MailerService $mailer
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $new = false;

        if (!$personne) {
            $new = true;
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        $form->handleRequest($request);    

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $directory = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $personne->setImage($uploaderService->uploadFile($photo, $directory));
            }

            if ($new) {
                $message = "la personne a été ajoutée";
                $personne->setCreatedBy($this->getUser());
            } else {
                $message = "la personne a été mise à jour";
            }

            $entityManager->persist($personne);
            $entityManager->flush();

            if ($new) {
                $addPersonneEvent = new AddPersonneEvent($personne);
                $this->logger->info('Dispatching AddPersonneEvent');
                $this->dispatcher->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
            }

            $mailMessage = $personne->getFirstname().' '.$personne->getName().' '.$message;
            $this->addFlash('success', $personne->getName() . ' ' . $message);

            $mailer->sendEmail(content:$mailMessage);
            
            return $this->redirectToRoute('app_personne_all');
        }

        return $this->render('personne/add.html.twig', ['form' => $form->createView()]);
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
    public function indexAll( ManagerRegistry $doctrine, int $nbre, int $page ): Response
    {

        //echo($this->helper->sayCC());
        
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
