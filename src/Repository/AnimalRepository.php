<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    // Méthode pour rechercher des animaux par état
    public function findByEtat($etat)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.etat = :etat')
            ->setParameter('etat', $etat)
            ->getQuery()
            ->getResult();
    }

    // Méthode pour rechercher des animaux par prénom 
    public function findByPrenom(string $prenom): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.prenom LIKE :prenom')  
            ->setParameter('prenom', '%' . $prenom . '%')  
            ->getQuery()
            ->getResult();
    }
    
}
