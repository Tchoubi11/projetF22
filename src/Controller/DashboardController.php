<?php

namespace App\Controller;

use App\Document\AnimalDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;


class DashboardController extends AbstractController
{
    public function animalStatistics(DocumentManager $dm): JsonResponse
{
    $animals = $dm->getRepository(AnimalDocument::class)
        ->findBy([], ['views' => 'DESC']); // ici je fais un tri décroissant par nombre de vues

    $statistics = array_map(fn($animal) => [
        'prenom' => $animal->getPrenom(),
        'views' => $animal->getViews(),
    ], $animals);

    return new JsonResponse($statistics);
}
#[Route('/animalDocument/getViews/{prenom}', name: 'animal_get_views', methods: ['GET'])]
public function getViewsByPrenom(string $prenom, DocumentManager $dm, Environment $twig): JsonResponse
{
    $animal = $dm->getRepository(AnimalDocument::class)->findOneBy(['prenom' => $prenom]);

    if (!$animal) {
        return new JsonResponse(['error' => 'Animal non trouvé'], Response::HTTP_NOT_FOUND);
    }

    $views = $animal->getViews();

    // Génération de l'HTML sans l'échapper
    $html = $twig->render('admin/_views.html.twig', [
        'views' => $views
    ]);

    return new JsonResponse([
        'views' => $views, 
        'html' => html_entity_decode($html) // Décode les entités HTML
    ]);
}


#[Route('/admin', name: 'admin_dashboard')]
public function dashboard(DocumentManager $dm): Response
{
    // Récupération de tous les animaux depuis MongoDB
    $animals = $dm->getRepository(AnimalDocument::class)->findAll();
    dump($animals);

    error_log("Nombre d'animaux trouvés : " . count($animals));

    return $this->render('admin/dashboard.html.twig', [
        'animals' => $animals, // Passe les animaux au template
    ]);
}

}
