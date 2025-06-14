<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CdaController extends AbstractController
{
    #[Route('/cda/test', name: 'app_cda')]
    public function index(): Response
    {
        return $this->render('cda/index.html.twig', [
            'controller_name' => 'CdaController',
        ]);
    }
}
