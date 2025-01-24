<?php

namespace App\Controller;

use App\Document\AnimalDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnimalDocumentController extends AbstractController
{
    #[Route('/animalDocument/incrementByPrenom/{prenom}', name: 'animal_increment_prenom', methods: ['POST'])]
public function incrementViewsByPrenom(string $prenom, DocumentManager $dm): JsonResponse
{
    error_log("Prénom reçu par le serveur : $prenom");

    // Recherche de l'animal par prénom
    $animal = $dm->getRepository(AnimalDocument::class)->findOneBy(['prenom' => $prenom]);

    if (!$animal) {
        error_log("Aucun animal trouvé avec le prénom : $prenom");
        return new JsonResponse(['error' => 'Animal non trouvé'], 404);
    }

    try {
        // Incrémentation des vues
        $animal->setViews($animal->getViews() + 1);
        $dm->flush();
        error_log("Vues mises à jour pour $prenom : " . $animal->getViews());
        return new JsonResponse(['views' => $animal->getViews()]);
    } catch (\Exception $e) {
        error_log("Erreur lors de la mise à jour : " . $e->getMessage());
        return new JsonResponse(['error' => 'Erreur serveur', 'details' => $e->getMessage()], 500);
    }
}


}
