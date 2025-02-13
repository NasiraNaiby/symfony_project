<?php

namespace App\Controller;

use App\Entity\Pigeons;
use App\Form\PigeonsType;
use App\Repository\PigeonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;


#[Route('/pigeons')]
final class PigeonsController extends AbstractController
{

    #[Route(name: 'app_pigeons_index', methods: ['GET'])]
    public function index(PigeonsRepository $pigeonsRepository): Response
    {
        return $this->render('pigeons/index.html.twig', [
            'pigeons' => $pigeonsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pigeons_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/')] string $imagesDirectory, EntityManagerInterface $entityManager): Response
    {
        $pigeon = new Pigeons();
        $form = $this->createForm(PigeonsType::class, $pigeon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the image file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the image file name
                // instead of its contents
                $pigeon->setImgSrc($newFilename);
            }
            $entityManager->persist($pigeon);
            $entityManager->flush();

            return $this->redirectToRoute('app_pigeons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pigeons/new.html.twig', [
            'pigeon' => $pigeon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pigeons_show', methods: ['GET'])]
    public function show(Pigeons $pigeon): Response
    {
        return $this->render('pigeons/show.html.twig', [
            'pigeon' => $pigeon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pigeons_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pigeons $pigeon, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/')] string $imagesDirectory, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        $form = $this->createForm(PigeonsType::class, $pigeon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move($imagesDirectory, $newFilename);

                    if ($pigeon->getImgSrc()) {
                        $oldImagePath = $imagesDirectory . '/' . $pigeon->getImgSrc();
                        if ($filesystem->exists($oldImagePath)) {
                            $filesystem->remove($oldImagePath);
                        }
                    }
                } catch (FileException $e) {
                
                }

                $pigeon->setImgSrc($newFilename);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_pigeons_index', [], Response::HTTP_SEE_OTHER);
        }

    return $this->render('pigeons/edit.html.twig', [
        'pigeon' => $pigeon,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_pigeons_delete', methods: ['POST'])]
    public function delete(Request $request, Pigeons $pigeon, EntityManagerInterface $entityManager, #[Autowire('%kernel.project_dir%/public/uploads/')] string $imagesDirectory, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pigeon->getId(), $request->request->get('_token'))) {
            if ($pigeon->getImgSrc()) {
                $imagePath = $imagesDirectory . '/' . $pigeon->getImgSrc();
                if ($filesystem->exists($imagePath)) {
                    $filesystem->remove($imagePath);
                }
            }
            $entityManager->remove($pigeon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pigeons_index', [], Response::HTTP_SEE_OTHER);
    }
}
