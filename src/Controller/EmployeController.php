<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use App\Entity\Avis;

class EmployeController extends AbstractController
{
    //Ici on gère la gestion des avis soumis qui ne sont pas encore visibles.
    private $avisRepository;

    // Injection de dépendance
    public function __construct(AvisRepository $avisRepository)
    {
        $this->avisRepository = $avisRepository;
    }

    // Route pour afficher le tableau de bord de l'employé
    #[Route('/employe/dashboard', name: 'employe_dashboard')]
    public function index(): Response
    {
        // On vérifie que l'utilisateur connecté a le rôle "ROLE_EMPLOYE"
        $this->denyAccessUnlessGranted('ROLE_EMPLOYE');

        // On retourne le template du tableau de bord employé
        return $this->render('employe/employe_dashboard.html.twig');
    }

    // Route pour la gestion des avis
    #[Route('/employe/avis', name: 'employe_avis')]
    public function gestionAvis(): Response
    {
        // On vérifie que l'utilisateur a bien le rôle "ROLE_EMPLOYE"
        $this->denyAccessUnlessGranted('ROLE_EMPLOYE');

        // On récupère les avis non visibles
        $avisNonVisibles = $this->avisRepository->findBy(['isVisible' => false]);

        // On retourne le template de gestion des avis
        return $this->render('employe/avis.html.twig', [
            'avisNonVisibles' => $avisNonVisibles,
        ]);
    }

    // Route pour valider un avis
    #[Route('/employe/avis/valider/{id}', name: 'employe_avis_valider')]
    public function validerAvis(Avis $avis): Response
    {
        // On rend l'avis visible
        $avis->setVisible(true);
        $this->avisRepository->save($avis, true);

        // Flash message pour confirmer la validation
        $this->addFlash('success', 'Avis validé avec succès.');

        // Redirection vers la gestion des avis
        return $this->redirectToRoute('employe_avis');
    }

    // Route pour supprimer un avis
    #[Route('/employe/avis/supprimer/{id}', name: 'employe_avis_supprimer')]
    public function supprimerAvis(Avis $avis): Response
    {
        // On supprime l'avis
        $this->avisRepository->remove($avis, true);

        // Flash message pour confirmer la suppression
        $this->addFlash('success', 'Avis supprimé avec succès.');

        // Redirection vers la gestion des avis
        return $this->redirectToRoute('employe_avis');
    }
}
