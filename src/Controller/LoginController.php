<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $error = null;

        // Check if form is submitted and validate the credentials
        if ($request->isMethod('POST')) {
            $login = $request->request->get('login');
            $mdp = $request->request->get('mdp');

            // Check if the username and password are correct
            if ($login === 'admin' && $mdp === 'admin') {
                // Redirect to the admin page on success
                return $this->redirectToRoute('app_admin');
            } else {
                // Show error message if credentials are incorrect
                $error = 'Invalid credentials';
            }
        }

        // Render the login page with error message if available
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error,
        ]);
    }
}
