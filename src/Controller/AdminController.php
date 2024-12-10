<?php

namespace App\Controller;

use App\Form\AdminType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Admin;
use App\Repository\AdminRepository;

class AdminController extends AbstractController
{
    private AdminRepository $adminRepository;


    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;

    }

    #[Route('/admin', name: 'app_admin')]
    public function index(AdminRepository $adminRepository): Response
    {
        $nbAdmins = $adminRepository->countAdmins(); // Replace `countAdmins` with your query logic.
        $averageAge = $this->adminRepository->getAverageAge();

        return $this->render('admin/index.html.twig', [
            'list' => $adminRepository->findAll(),
            'nbAdmins' => $nbAdmins,
            'averageAge' => $averageAge,
        ]);
    }
    /**
     * @Route("/admin/age-distribution", name="admin_age_distribution")
     */

    #[Route('/admin/create', name: 'admin_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $admin = new Admin();

        $entityManager->persist($admin);
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();
            $entityManager->flush();
            $this->addFlash('notice', 'Admin added successfully!');


            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/update/{id}', name: 'admin_update', methods: ['GET', 'POST'])]
    public function update(Request $request, $id): Response
    {
        $admin = $this->adminRepository->find($id);

        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adminRepository->save($admin);
            $this->addFlash('notice', 'Admin updated successfully');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/admin/delete/{id}", methods: ['POST', 'GET'])]
    public function delete(ManagerRegistry $doctrine, AdminRepository $adminRepository, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $admin = $adminRepository->find($id);

        $entityManager->remove($admin);
        $entityManager->flush();

        $this->addFlash('notice', 'Admin successfully deleted.');



        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/search', name: 'admin_search', methods: ['GET'])]
    public function searchByName(Request $request): Response
    {
        $name = $request->query->get('name', '');

        $results = $name
            ? $this->adminRepository->findByName($name)
            : [];

        return $this->render('admin/search.html.twig', [
            'results' => $results,
            'name' => $name,
        ]);
    }
    #[Route('/admin/average-age', name: 'admin_average_age', methods: ['GET'])]
    public function getAverageAge(): Response
    {
        $averageAge = $this->adminRepository->getAverageAge();

        return $this->render('admin/average_age.html.twig', [
            'averageAge' => $averageAge,
        ]);
    }

}
