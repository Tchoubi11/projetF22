<?php

namespace App\Controller;

use App\Document\AnimalDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(DocumentManager $dm): Response
    {
        //On récupére tous les animaux dans MongoDB
        $animals = $dm->getRepository(AnimalDocument::class)->findAll();

        // On passe les données à la vue Twig
        return $this->render('admin/dashboard.html.twig', [
            'animals' => $animals,
        ]);
    }
}
