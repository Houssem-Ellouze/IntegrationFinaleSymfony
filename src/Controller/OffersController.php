<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Form\OffersType;
use App\Repository\OffersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController
{
    #[Route('/offers/new', name: 'CreateOffer')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offers();
        $form = $this->createForm(OffersType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($offer);
                $entityManager->flush();

                $this->addFlash('success', 'Offer created successfully!');

                return $this->redirectToRoute('offers_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'An error occurred while creating the offer. Please try again.');
            }
        }

        return $this->render('offers/CreateOffer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/offers', name: 'offers_list')]
    public function list(OffersRepository $offersRepository): Response
    {
        $offers = $offersRepository->findAll();

        return $this->render('offers/ListeOffers.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/api/offers/statistics', name: 'offers_statistics', methods: ['GET'])]
    public function statistics(OffersRepository $offersRepository): JsonResponse
    {
        $offers = $offersRepository->findAll();
        
        // Calcul des statistiques
        $totalOffers = count($offers);
        $averageSalary = $this->calculateAverageSalary($offers);
        $requiredExperiences = array_map(fn($offer) => $offer->getAnneesExperience(),$offers);
        $salaries = array_map(fn($offer) => $offer->isSalaire(),$offers);
        // $expiredOffers = array_filter($offers, fn($offer) => $offer->isExpired());
        // $activeContracts = array_filter($offers, fn($offer) => $offer->isActive());
        // Retourner les statistiques sous forme de JSON
        return new JsonResponse([
            'totalOffers' => $totalOffers,
            'averageSalary' => $averageSalary,
            "requiredExperiences"=>$requiredExperiences,
            "salaries"=>$salaries
        ]);
    }

    private function calculateAverageSalary($offers): float
        {
            if (count($offers) === 0) {
                return 0;  // Pas d'offres, donc salaire moyen est 0
            }

            $totalSalary = array_reduce($offers, fn($sum, $offer) => $sum + $offer->getSalaire(), 0);
            return $totalSalary / count($offers);
        }
        

    #[Route('/offers/{id}', name: 'offers_view')]
    public function view(int $id, OffersRepository $offersRepository): Response
    {
        $offer = $offersRepository->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        return $this->render('offers/view.html.twig', [
            'offer' => $offer,
        ]);
    }

    #[Route('/offers/edit/{id}', name: 'offers_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        OffersRepository $offersRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $offer = $offersRepository->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        $form = $this->createForm(OffersType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Offer updated successfully.');

            return $this->redirectToRoute('offers_list');
        }

        return $this->render('offers/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    #[Route('/offers/delete/{id}', name: 'offers_delete', methods: ['POST'])]
    public function delete(
        int $id,
        OffersRepository $offersRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $offer = $offersRepository->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        $entityManager->remove($offer);
        $entityManager->flush();
        $this->addFlash('success', 'Offer deleted successfully.');

        return $this->redirectToRoute('offers_list');
    }
}
