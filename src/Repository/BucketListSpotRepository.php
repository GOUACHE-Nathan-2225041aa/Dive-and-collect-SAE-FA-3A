<?php

namespace App\Repository;

use App\Entity\BucketListSpot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BucketListSpot>
 *
 * @method BucketListSpot|null find($id, $lockMode = null, $lockVersion = null)
 * @method BucketListSpot|null findOneBy(array $criteria, array $orderBy = null)
 * @method BucketListSpot[]    findAll()
 * @method BucketListSpot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BucketListSpotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BucketListSpot::class);
    }

    //    /**
    //     * @return BucketListSpot[] Returns an array of BucketListSpot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BucketListSpot
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
