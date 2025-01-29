<?php

// src/Controller/EmployeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EmployeController extends AbstractController
{
    #[Route('/employe/dashboard', name: 'employe_dashboard')]
    public function dashboard(): Response
    {
        // ic je vérifie que l'utilisateur connecté a le rôle "ROLE_EMPLOYE"
        if (!$this->isGranted('ROLE_EMPLOYE')) {
            throw new AccessDeniedException('Accès non autorisé.');
        }

        // Rendre le template du dashboard employé
        return $this->render('employe/dashboard_employe.html.twig');
    }
}
