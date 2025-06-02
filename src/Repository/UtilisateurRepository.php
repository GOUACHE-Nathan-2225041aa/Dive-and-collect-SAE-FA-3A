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

    public function findTopByRole(int $limit = 5, array $roles = ['ROLE_ONG', 'ROLE_USER']): array
    {
        $qb = $this->createQueryBuilder('u')
            ->addSelect('b') // on récupère les badges en même temps
            ->leftJoin('u.badges', 'b')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('u.points > 0') // on ne garde que les utilisateurs avec des points
            ->setMaxResults($limit)
            ->orderBy('u.points', 'DESC');

        $result = [];
        foreach ($roles as $role) {
            $result[$role] = $qb->setParameter('role', "%{$role}%")
                ->getQuery()
                ->getResult();
        }

        return $result;
    }
}
