<?php

namespace App\Controller;

use App\Document\AnimalDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnimalDocumentController extends AbstractController
{
    #[Route('/animalDocument/{prenom}', name: 'animal_view', methods: ['GET'])]
    public function viewAnimal(string $prenom, DocumentManager $dm): JsonResponse
    {
        // Ici on recherche l'animal dans MongoDB
        $animalDocument = $dm->getRepository(AnimalDocument::class)->findOneBy(['prenom' => $prenom]);

        if (!$animalDocument) {
            // Si l'animal n'existe pas, créer un nouveau document
            $animalDocument = new AnimalDocument();
            $animalDocument->setPrenom($prenom);
            $animalDocument->setViews(1); // Initialiser les vues à 1
        } else {
            // Incrémentation du compteur de vues
            $animalDocument->incrementViews();
        }

        // Sauvegarde des modifications dans MongoDB
        $dm->persist($animalDocument);
        $dm->flush();

        // Retour de la réponse JSON avec les détails de l'animal
        return $this->json([
            'message' => "Consultation de l'animal mise à jour.",
            'animal' => $animalDocument->getPrenom(),
            'views' => $animalDocument->getViews(),
        ]);
    }
}
