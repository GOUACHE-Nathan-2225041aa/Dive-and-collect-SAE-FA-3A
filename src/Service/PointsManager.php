<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class PointsManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Modifie les points d'un utilisateur.
     */
    public function updatePoints(Utilisateur $utilisateur, int $points, string $reason = null): void
    {
        $current = $utilisateur->getPoints();
        $utilisateur->setPoints($current + $points);

        $this->em->persist($utilisateur);
        $this->em->flush();
    }

    /**
     * Réinitialise les points d'un utilisateur.
     */
    public function resetPoints(Utilisateur $utilisateur): void
    {
        $utilisateur->setPoints(0);
        $this->em->persist($utilisateur);
        $this->em->flush();
    }

    /**
     * @param Utilisateur $utilisateur
     * @return void
     */
    private function checkPointsAndUpdateBadge(Utilisateur $utilisateur): void
    {
        $points = $utilisateur->getPoints();

//        if ($points < 50)

    }

}
