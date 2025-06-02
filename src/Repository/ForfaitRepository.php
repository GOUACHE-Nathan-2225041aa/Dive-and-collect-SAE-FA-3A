<?php

namespace App\Repository;

use App\Entity\Forfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forfait>
 */
class ForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forfait::class);
    }

    public function findAllWithLots(): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.lots', 'l')
            ->addSelect('l')
            ->getQuery()
            ->getResult();
    }
}
