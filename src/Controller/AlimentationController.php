<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AlimentationController extends AbstractController
{
    #[Route('/alimentation', name: 'app_alimentation')]
    public function index(): Response
    {
        return $this->render('alimentation/index.html.twig', [
            'controller_name' => 'AlimentationController',
        ]);
    }
}
