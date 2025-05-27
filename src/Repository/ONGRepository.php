<?php

namespace App\Repository;

use App\Entity\ONG;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ONG>
 */
class ONGRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ONG::class);
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
