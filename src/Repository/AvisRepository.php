<?php
namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    public function save(Avis $entity, bool $flush = false): void
    {
        $em = $this->getEntityManager(); // Utiliser getEntityManager() pour obtenir l'EntityManager
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    public function remove(Avis $entity, bool $flush = false): void
    {
        $em = $this->getEntityManager(); // Utiliser getEntityManager() pour obtenir l'EntityManager
        $em->remove($entity);
        if ($flush) {
            $em->flush();
        }
    }
}
