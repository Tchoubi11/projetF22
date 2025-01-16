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
                'image' => 'public/uploads/images/6788dedce7b80.jpg',
                'animaux' => [
                    ['prenom' => 'Lion', 'race' => 'Panthera leo', 'image' => 'lion.jpg'],
                    ['prenom' => 'Gazelle', 'race' => 'Gazella', 'image' => 'gazelle.jpg'],
                    ['prenom' => 'Zèbre', 'race' => 'Equus zebra', 'image' => 'zebre.jpg'],
                ],
            ],
            [
                'nom' => 'Jungle',
                'description' => 'Forêt dense et humide.',
                'image' => 'public/uploads/images/6788df7f8ae1e.jpg',
                'animaux' => [
                    ['prenom' => 'Tigre', 'race' => 'Panthera tigris', 'image' => 'tigre.jpg'],
                    ['prenom' => 'Singe', 'race' => 'Macaca', 'image' => 'singe.jpg'],
                    ['prenom' => 'Serpent', 'race' => 'Serpentes', 'image' => 'serpent.jpg'],
                ],
            ],
            [
                'nom' => 'Marais',
                'description' => 'Zone humide avec une végétation dense.',
                'image' => 'public/uploads/images/6788df98f12a3.jpg',
                'animaux' => [
                    ['prenom' => 'Crocodile', 'race' => 'Crocodylus niloticus', 'image' => 'crocodile.jpg'],
                    ['prenom' => 'Grenouille', 'race' => 'Anura', 'image' => 'grenouille.jpg'],
                    ['prenom' => 'Ibis', 'race' => 'Threskiornithidae', 'image' => 'ibis.jpg'],
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
