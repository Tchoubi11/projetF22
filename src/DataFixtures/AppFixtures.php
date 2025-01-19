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
                    ['prenom' => 'Lion', 'race' => 'Panthera leo', 'image' => 'uploads/images/6784198bc1b3b.jpg'],
                    ['prenom' => 'Gazelle', 'race' => 'Gazella', 'image' => 'uploads/images/67893d91b355d.jpg'],
                    ['prenom' => 'Zèbre', 'race' => 'Equus zebra', 'image' => 'uploads/images/67893d0410cae.jpg'],
                    ['prenom' => 'Gorille', 'race' => 'Gorilla gorilla', 'image' => 'uploads/images/6788dedce7b80.jpg'], 
                    ['prenom' => 'Léopard', 'race' => 'Panthera pardus', 'image' => 'uploads/images/67841a1918305.jpg'], 
                ],
            ],
            [
                'nom' => 'Jungle',
                'description' => 'Forêt dense et humide.',
                'image' => 'uploads/images/678cee02e776c.jpg',
                'animaux' => [
                    ['prenom' => 'Tigre', 'race' => 'Panthera tigris', 'image' => 'uploads/images/67897e4086ef8.jpg'],
                    ['prenom' => 'Singe', 'race' => 'Macaca mulatta', 'image' => 'uploads/images/678412c5ce6aa.jpg'], 
                    ['prenom' => 'Capibara', 'race' => 'Hydrochoerus hydrochaeris', 'image' => '/uploads/images/6788defcdf4a5.jpg'], 
                    ['prenom' => 'Jaguar', 'race' => 'Panthera onca', 'image' => 'uploads/images/6788df28121ef.jpg'], 
                    ['prenom' => 'Lynx', 'race' => 'Lynx lynx', 'image' => 'uploads/images/6788df491c352.jpg'], 
                ],
            ],
            [
                'nom' => 'Marais',
                'description' => 'Zone humide et végétation dense.',
                'image' => 'uploads/images/678cea6a0f09d.jpg',
                'animaux' => [
                    ['prenom' => 'Crocodile', 'race' => 'Crocodylus niloticus', 'image' => 'uploads/images/67897f3a31afb.jpg'],
                    ['prenom' => 'Grenouille', 'race' => 'Anura', 'image' => 'uploads/images/67897f9acb62c.jpg'],
                    ['prenom' => 'Ibis', 'race' => 'Threskiornithidae', 'image' => 'uploads/images/67897e9296d3e.jpg'],
                    ['prenom' => 'Tortue', 'race' => 'Trachemys scripta', 'image' => 'uploads/images/6789803803ba9.jpg'],
                    ['prenom' => 'Héron', 'race' => 'Ardeidae', 'image' => 'uploads/images/67897ff81ad1a.jpg'], 
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

                $manager->persist($animal);
            }
        }

        $manager->flush();
    }
}
