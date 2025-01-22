<?php

namespace App\Repository;

use App\Entity\DiveSpecies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiveSpecies>
 *
 * @method DiveSpecies|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiveSpecies|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiveSpecies[]    findAll()
 * @method DiveSpecies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiveSpeciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiveSpecies::class);
    }

    //    /**
    //     * @return DiveSpecies[] Returns an array of DiveSpecies objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DiveSpecies
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
