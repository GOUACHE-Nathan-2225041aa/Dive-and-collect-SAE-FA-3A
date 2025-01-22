<?php

namespace App\Repository;

use App\Entity\BucketList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BucketList>
 *
 * @method BucketList|null find($id, $lockMode = null, $lockVersion = null)
 * @method BucketList|null findOneBy(array $criteria, array $orderBy = null)
 * @method BucketList[]    findAll()
 * @method BucketList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BucketListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BucketList::class);
    }

    //    /**
    //     * @return BucketList[] Returns an array of BucketList objects
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

    //    public function findOneBySomeField($value): ?BucketList
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
