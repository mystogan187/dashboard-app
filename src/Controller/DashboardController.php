<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/{reactRouting}', name: 'app_home', requirements: ['reactRouting' => '.*'], defaults: ['reactRouting' => null], methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
}