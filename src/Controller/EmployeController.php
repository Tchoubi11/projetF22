<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EmployeController extends AbstractController
{
    #[Route('/employe/dashboard', name: 'employe_dashboard')]
    public function index(): Response
    {
        // on vérifie que l'utilisateur connecté a le rôle "ROLE_EMPLOYE"
        if (!$this->isGranted('ROLE_EMPLOYE')) {
            throw new AccessDeniedException('Accès non autorisé.');
        }

        // on retourne le template du dashboard employé
        return $this->render('employe/employe_dashboard.html.twig');
    }
}
