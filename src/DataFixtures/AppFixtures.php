<?php

namespace App\DataFixtures;

use App\Entity\Habitat;
use App\Entity\Animal;
use App\Entity\Image;
use App\Entity\Race;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $habitats = [
            [
                'nom' => 'Savane',
                'description' => 'Vaste plaine herbeuse.',
                'image' => 'uploads/images/678ce878bda16.jpg',
                'animaux' => [
                    [
                        'prenom' => 'Lion',
                        'race' => 'Panthera leo',
                        'image' => 'uploads/images/6784198bc1b3b.jpg',
                        'etat' => 'En bonne santé',
                        'nourriture' => 'Viande rouge',
                        'grammage' => 3.5,
                        'dateDePassage' => new \DateTime('2025-01-18 10:00:00')
                    ],
                    [
                        'prenom' => 'Gazelle',
                        'race' => 'Gazella',
                        'image' => 'uploads/images/67893d91b355d.jpg',
                        'etat' => 'Fracture à la patte arrière',
                        'nourriture' => 'Herbe',
                        'grammage' => 2.0,
                        'dateDePassage' => new \DateTime('2025-01-17 12:00:00')
                    ],
                    // Ajoutez d'autres animaux ici avec les mêmes propriétés...
                ],
            ],
            [
                'nom' => 'Jungle',
                'description' => 'Forêt dense et humide.',
                'image' => 'uploads/images/678cee02e776c.jpg',
                'animaux' => [
                    [
                        'prenom' => 'Tigre',
                        'race' => 'Panthera tigris',
                        'image' => 'uploads/images/67897e4086ef8.jpg',
                        'etat' => 'Fatigué, mais stable',
                        'nourriture' => 'Viande de poulet',
                        'grammage' => 4.0,
                        'dateDePassage' => new \DateTime('2025-01-19 08:30:00')
                    ],
                    // Ajoutez d'autres animaux ici avec les mêmes propriétés...
                ],
            ],
            [
                'nom' => 'Marais',
                'description' => 'Zone humide et végétation dense.',
                'image' => 'uploads/images/678cea6a0f09d.jpg',
                'animaux' => [
                    [
                        'prenom' => 'Crocodile',
                        'race' => 'Crocodylus niloticus',
                        'image' => 'uploads/images/67897f3a31afb.jpg',
                        'etat' => 'En bonne santé',
                        'nourriture' => 'Poisson',
                        'grammage' => 2.5,
                        'dateDePassage' => new \DateTime('2025-01-15 14:00:00')
                    ],
                    // Ajoutez d'autres animaux ici avec les mêmes propriétés...
                ],
            ],
        ];

        foreach ($habitats as $habitatData) {
            $habitat = new Habitat();
            $habitat->setNom($habitatData['nom']);
            $habitat->setDescription($habitatData['description']);

            $image = new Image();
            $image->setImagePath($habitatData['image']);
            $habitat->addImage($image);

            $manager->persist($image);
            $manager->persist($habitat);

            foreach ($habitatData['animaux'] as $animalData) {
                // Vérifier si la race existe déjà ou la créer
                $race = $manager->getRepository(Race::class)->findOneBy(['label' => $animalData['race']]);
                if (!$race) {
                    $race = new Race();
                    $race->setLabel($animalData['race']);
                    $manager->persist($race);
                }

                // Créer l'animal et l'associer à l'habitat et à la race
                $animal = new Animal();
                $animal->setPrenom($animalData['prenom']);
                $animal->setImage($animalData['image']);
                $animal->setHabitat($habitat);
                $animal->setRace($race); // Associe la bonne race

                // Ajouter les nouvelles propriétés à l'animal
                $animal->setEtat($animalData['etat']);
                $animal->setNourriture($animalData['nourriture']);
                $animal->setGrammage($animalData['grammage']);
                $animal->setDateDePassage($animalData['dateDePassage']);

                $manager->persist($animal);
            }
        }

        $manager->flush();
    }
}
