<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Race;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReorganizeRaceIdsCommand extends Command
{
    protected static $defaultName = 'app:reorganize-race-ids';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Réorganise les IDs dans la table race et met à jour les relations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->entityManager->getConnection();

        // Sauvegarder l'ordre actuel des races
        $races = $this->entityManager->getRepository(Race::class)->findBy([], ['id' => 'ASC']);

        $newId = 1;
        foreach ($races as $race) {
            // Mise à jour des animaux pour chaque nouvelle ID
            $connection->executeStatement('UPDATE animal SET race_id = :newId WHERE race_id = :oldId', [
                'newId' => $newId,
                'oldId' => $race->getId(),
            ]);

            // Mise à jour de la race elle-même
            $connection->executeStatement('UPDATE race SET id = :newId WHERE id = :oldId', [
                'newId' => $newId,
                'oldId' => $race->getId(),
            ]);

            $newId++;
        }

        $this->entityManager->flush();

        $output->writeln('<info>Réorganisation des IDs de la table race terminée avec succès.</info>');

        return Command::SUCCESS;
    }
}
