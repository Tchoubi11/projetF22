<?php

namespace App\DataFixtures;

use App\Entity\Habitat;
use App\Entity\Animal;
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
                'image' => 'public/uploads/images/678980c33c04d.jpg',
                'animaux' => [
                    ['prenom' => 'Lion', 'race' => 'Panthera leo', 'image' => 'public/uploads/images/6784198bc1b3b.jpg'],
                    ['prenom' => 'Gazelle', 'race' => 'Gazella', 'image' => 'public/uploads/images/67893d91b355d.jpg'],
                    ['prenom' => 'Zèbre', 'race' => 'Equus zebra', 'image' => 'public/uploads/images/67893d0410cae.jpg'],
                    ['prenom' => 'Gorille', 'race' => 'Gorilla gorilla', 'image' => 'public/uploads/images/6788dedce7b80.jpg'], 
                    ['prenom' => 'Léopard', 'race' => 'Panthera pardus', 'image' => 'public/uploads/images/67841a1918305.jpg'], 
                ],
            ],
            [
                'nom' => 'Jungle',
                'description' => 'Forêt dense et humide.',
                'image' => 'public/uploads/images/6788dfdc973a4.jpg',
                'animaux' => [
                    ['prenom' => 'Tigre', 'race' => 'Panthera tigris', 'image' => 'public/uploads/images/67897e4086ef8.jpg'],
                    ['prenom' => 'Singe', 'race' => 'Macaca mulatta', 'image' => 'public/uploads/images/678412c5ce6aa.jpg'], 
                    ['prenom' => 'Capibara', 'race' => 'Hydrochoerus hydrochaeris', 'image' => 'public/uploads/images/6788defcdf4a5.jpg'], 
                    ['prenom' => 'Jaguar', 'race' => 'Panthera onca', 'image' => 'public/uploads/images/6788df28121ef.jpg'], 
                    ['prenom' => 'Lynx', 'race' => 'Lynx lynx', 'image' => 'public/uploads/images/6788df491c352.jpg'], 
                ],
            ],
            [
                'nom' => 'Marais',
                'description' => 'Zone humide avec une végétation dense.',
                'image' => 'public/uploads/images/67897c6587286.jpg',
                'animaux' => [
                    ['prenom' => 'Crocodile', 'race' => 'Crocodylus niloticus', 'image' => 'public/uploads/images/67897f3a31afb.jpg'],
                    ['prenom' => 'Grenouille', 'race' => 'Anura', 'image' => 'public/uploads/images/67897f9acb62c.jpg'],
                    ['prenom' => 'Ibis', 'race' => 'Threskiornithidae', 'image' => 'public/uploads/images/67897e9296d3e.jpg'],
                    ['prenom' => 'Tortue', 'race' => 'Trachemys scripta', 'image' => 'public/uploads/images/6789803803ba9.jpg'],
                    ['prenom' => 'Héron', 'race' => 'Ardeidae', 'image' => 'public/uploads/images/67897ff81ad1a.jpg'], 
                ],
            ],
        ];

        foreach ($habitats as $habitatData) {
            $habitat = new Habitat();
            $habitat->setNom($habitatData['nom']);
            $habitat->setDescription($habitatData['description']);
            $habitat->setImage($habitatData['image']);
            $manager->persist($habitat);

            // Ajouter les animaux associés à cet habitat
            foreach ($habitatData['animaux'] as $animalData) {
                $animal = new Animal();
                $animal->setPrenom($animalData['prenom']);
                $animal->setRace($animalData['race']);
                $animal->setImage($animalData['image']);
                $animal->setHabitat($habitat);
                $manager->persist($animal);
            }
        }

        $manager->flush();
    }
}
