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
        $users = $this->createQueryBuilder('u')
            ->addSelect('b') // on récupère les badges en même temps
            ->leftJoin('u.badges', 'b')
            ->orderBy('u.points', 'DESC')
            ->getQuery()
            ->getResult();

        // On filtre d'abord
        $ongs = array_filter($users, function ($user) {
            return in_array('ROLE_ONG', $user->getRoles(), true);
        });

        // Puis on applique le limit à la liste filtrée
        return array_slice($ongs, 0, $limit);
    }
}
