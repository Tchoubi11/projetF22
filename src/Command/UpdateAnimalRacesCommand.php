<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Animal;
use App\Entity\Race;

class UpdateAnimalRacesCommand extends Command
{
    protected static $defaultName = 'app:update-animal-races';
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Met à jour les races associées aux animaux.')
            ->setHelp('Cette commande associe les animaux aux races correctes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $animalRaceMapping = [
            'Lion' => 'Panthera leo',
            'Gazelle' => 'Gazella',
            'Zèbre' => 'Equus zebra',
            'Gorille' => 'Gorilla gorilla',
            'Léopard' => 'Panthera pardus',
            'Tigre' => 'Panthera tigris',
            'Singe' => 'Macaca mulatta',
            'Capibara' => 'Hydrochoerus hydrochaeris',
            'Jaguar' => 'Panthera onca',
            'Lynx' => 'Lynx lynx',
            'Crocodile' => 'Crocodylus niloticus',
            'Grenouille' => 'Anura',
            'Ibis' => 'Threskiornithidae',
            'Tortue' => 'Trachemys scripta',
            'Héron' => 'Ardeidae',
        ];

        foreach ($animalRaceMapping as $animalName => $raceLabel) {
            $animal = $this->entityManager->getRepository(Animal::class)->findOneBy(['prenom' => $animalName]);
            if ($animal) {
                $race = $this->entityManager->getRepository(Race::class)->findOneBy(['label' => $raceLabel]);
                if ($race) {
                    $animal->setRace($race);
                    $this->entityManager->persist($animal);
                    $output->writeln("Race mise à jour pour l'animal : $animalName -> $raceLabel");
                } else {
                    $output->writeln("Race non trouvée : $raceLabel");
                }
            } else {
                $output->writeln("Animal non trouvé : $animalName");
            }
        }

        $this->entityManager->flush();
        $output->writeln('Mise à jour terminée.');
        return Command::SUCCESS;
    }
}
