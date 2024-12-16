<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClasseController extends AbstractController
{
  


    #[Route('/', name:'home')]
    public function Home(): Response
    {
        return $this->render('/Home/Home.html.twig');
    }

    
    #[Route('/about', name:'About')]
    public function About(): Response
    {
        return $this->render('/Home/About.html.twig');
    }

    #[Route('/dashboard', name:'dash')]
    public function Dashboard(): Response
    {
        return $this->render('/dashboard/dash.html.twig');
    }
   
}