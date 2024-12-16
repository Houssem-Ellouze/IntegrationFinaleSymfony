<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Repository\RecruteurRepository;
use App\Repository\RecruteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/recruteur')]
final class RecruteurController extends AbstractController
{
    #[Route(name: 'app_recruteur_index', methods: ['GET'])]
    public function index(Request $request, RecruteurRepository $repository)
    {
        // Get filter criteria from query parameters
        $filters = [
            'nom' => $request->query->get('nom'),
            'email' => $request->query->get('email'),
            'statut' => $request->query->get('statut'),
        ];

        // Apply filters
        $queryBuilder = $repository->filter($filters);
        $recruteurs = $queryBuilder->getQuery()->getResult();

        // Get statistics
        $statistics = $repository->getStatistics();

        return $this->render('recruteur/index.html.twig', [
            'recruteurs' => $recruteurs,
            'statistics' => $statistics,
        ]);
    }

    #[Route('/new', name: 'app_recruteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $recruteur = new Recruteur();
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the profilePicture file upload
            $profilePictureFile = $form->get('profilePicture')->getData();

            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Sanitize the filename
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                try {
                    // Move the file to the directory where profile pictures are stored
                    $profilePictureFile->move(
                        $this->getParameter('uploads_directory'), // Directory defined in services.yaml
                        $newFilename
                    );
                    // Set the file name in the entity
                    $recruteur->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    // Handle the exception and provide feedback
                    $this->addFlash('error', 'An error occurred while uploading the file.');
                }
            }

            // Persist the recruteur entity
            $entityManager->persist($recruteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_recruteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recruteur/new.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_recruteur_show', methods: ['GET'])]
    public function show(Recruteur $recruteur): Response
    {
        return $this->render('recruteur/show.html.twig', [
            'recruteur' => $recruteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recruteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recruteur $recruteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recruteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recruteur/edit.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recruteur_delete', methods: ['POST'])]
    public function delete(Request $request, Recruteur $recruteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recruteur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recruteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recruteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
