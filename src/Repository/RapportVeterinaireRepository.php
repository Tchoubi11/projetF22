<?php

namespace App\Repository;

use App\Entity\RapportVeterinaire;
use App\Entity\Animal; 
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RapportVeterinaire>
 */
class RapportVeterinaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportVeterinaire::class);
    }

    /**
     * Recherche des rapports vétérinaires par animal ou par date
     *
     * @param Animal|null $animal
     * @param \DateTimeInterface|null $date
     * @return RapportVeterinaire[]
     */
    public function findByAnimalOrDate(?Animal $animal, ?\DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('r'); // Alias 'r' pour RapportVeterinaire

        // Ajout d'une condition si $animal est fourni
        if ($animal !== null) {
            $qb->andWhere('r.animal = :animal')
               ->setParameter('animal', $animal);
        }

        // Ajout d'une condition si $date est fourni
        if ($date !== null) {
            $qb->andWhere('r.date = :date')
               ->setParameter('date', $date);
        }

        return $qb->getQuery()->getResult();
    }
    // ajout d'une méthode pour compter les consultations 
    public function countConsultationsByAnimal(): array
   {
       return $this->createQueryBuilder('r')
        ->select('a.prenom, COUNT(r.id) as consultations')
        ->join('r.animal', 'a')
        ->groupBy('a.id')
        ->getQuery()
        ->getResult();
   }
 
}
