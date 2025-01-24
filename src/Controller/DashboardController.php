<?php

namespace App\Controller;

use App\Document\AnimalDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
}
