<?php

namespace App\Repository;

use App\Entity\Alimentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alimentation>
 */
class AlimentationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alimentation::class);
    }

    /**
     * Récupère toutes les alimentations triées par dateHeure (du plus récent au plus ancien)
     *
     * @return Alimentation[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les alimentations pour un animal donné.
     *
     * @param int $animal L'ID de l'animal concerné
     * @return Alimentation[]
     */
    public function findByAnimal($animal): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.animal = :animal')
            ->setParameter('animal', $animal)
            ->orderBy('a.dateHeure', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
