<?php 

namespace App\DataFixtures;

use App\Entity\Habitat;
use App\Entity\Animal;
use App\Entity\Image;
use App\Entity\Race;
use App\Entity\RapportVeterinaire;
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
                        'nourriture' => 'Viande',
                        'grammage' => 5.0,
                        'dateDePassage' => '2025-01-01 10:00:00',
                    ],
                    [
                        'prenom' => 'Gazelle',
                        'race' => 'Gazella',
                        'image' => 'uploads/images/67893d91b355d.jpg',
                        'etat' => 'Nerveuse',
                        'nourriture' => 'Herbe',
                        'grammage' => 3.5,
                        'dateDePassage' => '2025-01-02 11:30:00',
                    ],
                    [
                        'prenom' => 'Zèbre',
                        'race' => 'Equus zebra',
                        'image' => 'uploads/images/67893d0410cae.jpg',
                        'etat' => 'Calme',
                        'nourriture' => 'Herbe',
                        'grammage' => 4.0,
                        'dateDePassage' => '2025-01-03 08:45:00',
                    ],
                    [
                        'prenom' => 'Gorille',
                        'race' => 'Gorilla gorilla',
                        'image' => 'uploads/images/6788dedce7b80.jpg',
                        'etat' => 'Agité',
                        'nourriture' => 'Fruits',
                        'grammage' => 3.8,
                        'dateDePassage' => '2025-01-04 14:00:00',
                    ],
                    [
                        'prenom' => 'Léopard',
                        'race' => 'Panthera pardus',
                        'image' => 'uploads/images/67841a1918305.jpg',
                        'etat' => 'Agressif',
                        'nourriture' => 'Viande',
                        'grammage' => 4.5,
                        'dateDePassage' => '2025-01-05 09:15:00',
                    ],
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
                        'etat' => 'Affamé',
                        'nourriture' => 'Viande',
                        'grammage' => 6.0,
                        'dateDePassage' => '2025-01-06 12:00:00',
                    ],
                    [
                        'prenom' => 'Singe',
                        'race' => 'Macaca mulatta',
                        'image' => 'uploads/images/678412c5ce6aa.jpg',
                        'etat' => 'Curieux',
                        'nourriture' => 'Fruits',
                        'grammage' => 2.5,
                        'dateDePassage' => '2025-01-07 15:00:00',
                    ],
                    [
                        'prenom' => 'Capibara',
                        'race' => 'Hydrochoerus hydrochaeris',
                        'image' => '/uploads/images/6788defcdf4a5.jpg',
                        'etat' => 'Paisible',
                        'nourriture' => 'Herbe',
                        'grammage' => 3.2,
                        'dateDePassage' => '2025-01-08 10:30:00',
                    ],
                    [
                        'prenom' => 'Jaguar',
                        'race' => 'Panthera onca',
                        'image' => 'uploads/images/6788df28121ef.jpg',
                        'etat' => 'Féroce',
                        'nourriture' => 'Viande',
                        'grammage' => 5.5,
                        'dateDePassage' => '2025-01-09 11:45:00',
                    ],
                    [
                        'prenom' => 'Lynx',
                        'race' => 'Lynx lynx',
                        'image' => 'uploads/images/6788df491c352.jpg',
                        'etat' => 'Méfiant',
                        'nourriture' => 'Petits mammifères',
                        'grammage' => 2.7,
                        'dateDePassage' => '2025-01-10 16:00:00',
                    ],
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
                        'etat' => 'Lent',
                        'nourriture' => 'Poissons',
                        'grammage' => 7.0,
                        'dateDePassage' => '2025-01-11 13:30:00',
                    ],
                    [
                        'prenom' => 'Grenouille',
                        'race' => 'Anura',
                        'image' => 'uploads/images/67897f9acb62c.jpg',
                        'etat' => 'Sautillante',
                        'nourriture' => 'Insectes',
                        'grammage' => 0.2,
                        'dateDePassage' => '2025-01-12 17:45:00',
                    ],
                    [
                        'prenom' => 'Ibis',
                        'race' => 'Threskiornithidae',
                        'image' => 'uploads/images/67897e9296d3e.jpg',
                        'etat' => 'Actif',
                        'nourriture' => 'Poissons',
                        'grammage' => 1.5,
                        'dateDePassage' => '2025-01-13 07:15:00',
                    ],
                    [
                        'prenom' => 'Tortue',
                        'race' => 'Trachemys scripta',
                        'image' => 'uploads/images/6789803803ba9.jpg',
                        'etat' => 'Lente',
                        'nourriture' => 'Plantes aquatiques',
                        'grammage' => 0.8,
                        'dateDePassage' => '2025-01-14 09:00:00',
                    ],
                    [
                        'prenom' => 'Héron',
                        'race' => 'Ardeidae',
                        'image' => 'uploads/images/67897ff81ad1a.jpg',
                        'etat' => 'Calme',
                        'nourriture' => 'Poissons',
                        'grammage' => 1.8,
                        'dateDePassage' => '2025-01-15 14:30:00',
                    ],
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
                $race = $manager->getRepository(Race::class)->findOneBy(['label' => $animalData['race']]);
                if (!$race) {
                    $race = new Race();
                    $race->setLabel($animalData['race']);
                    $manager->persist($race);
                }

                $animal = new Animal();
                $animal->setPrenom($animalData['prenom']);
                $animal->setImage($animalData['image']);
                $animal->setHabitat($habitat);
                $animal->setRace($race);
                $animal->setEtat($animalData['etat']);
                $manager->persist($animal);

                $rapport = new RapportVeterinaire();
                $rapport->setAnimal($animal);
                $rapport->setDate(new \DateTime($animalData['dateDePassage']));
                $rapport->setDetail("Rapport de santé pour l'animal : {$animalData['prenom']}.");
                $manager->persist($rapport);

                $animal->setRapportVeterinaire($rapport);
            }
        }

        $manager->flush();
    }
}
