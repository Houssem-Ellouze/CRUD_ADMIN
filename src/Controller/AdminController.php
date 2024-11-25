<?php

namespace App\Controller;
use App\Form\AdminType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

// Importez les bonnes classes
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Doctrine\ORM\EntityManagerInterface; // Ajoutez cette ligne pour l'EntityManager

use App\Entity\Admin;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method getDoctrine()
 */
class AdminController extends AbstractController
{
    private $adminRepository;

    // Inject the AdminRepository into the controller
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        // Fetch all admin data using the injected repository
        $data = $this->adminRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'list' => $data,
        ]);
    }

    #[Route('/admin/create', name: 'admin_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $admin = new Admin(); // Créez une nouvelle instance de Admin

        $entityManager->persist($admin);
        $form = $this->createForm(AdminType::class, $admin); // Créez le formulaire

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();
            $entityManager->flush();
            $this->addFlash('notice', 'Admin added successfully !');
            return $this->redirectToRoute('app_admin'); // Redirige vers la liste

        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(Request $request, $id)
    {
        // Retrieve the Admin entity from the database
        $admin = $this->adminRepository->find($id); // Use the find method of the repository

        if (!$admin) {
            throw $this->createNotFoundException('No admin found for id ' . $id);
        }

        // Create the form for updating the Admin
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated admin entity
            $this->adminRepository->save($admin);
            $this->addFlash('notice', 'Admin updated successfully');

            return $this->redirectToRoute('app_admin'); // Assuming 'admin_list' is a valid route
        }

        return $this->render('admin/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    #[Route('/delete/{id}', name: 'delete', methods: ['POST', 'GET'])]
    public function delete(ManagerRegistry $doctrine, AdminRepository $adminRepository, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        // Retrieve the Admin entity by ID
        $admin = $adminRepository->find($id);

        if (!$admin) {
            throw $this->createNotFoundException('No admin found with ID ' . $id);
        }

        // Remove the admin entity
        $entityManager->remove($admin);
        $entityManager->flush();

        // Add a flash message for user feedback
        $this->addFlash('notice', 'Admin successfully deleted.');

        // Redirect to the admin list page
        return $this->redirectToRoute('app_admin');
    }





}
