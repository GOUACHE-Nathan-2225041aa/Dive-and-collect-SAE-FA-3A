<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function findTopOngs(int $limit = 5): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.points', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
