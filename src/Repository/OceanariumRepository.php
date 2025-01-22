<?php

namespace App\Repository;

use App\Entity\Oceanarium;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Oceanarium>
 *
 * @method Oceanarium|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oceanarium|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oceanarium[]    findAll()
 * @method Oceanarium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OceanariumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oceanarium::class);
    }

    //    /**
    //     * @return Oceanarium[] Returns an array of Oceanarium objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Oceanarium
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
