<?php

namespace App\Service;

use App\Entity\Badge;
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
    public function updatePoints(Utilisateur $utilisateur, int $points): void
    {
        $current = $utilisateur->getPoints();
        $utilisateur->setPoints($current + $points);

        $this->em->persist($utilisateur);
        $this->em->flush();

        $this->checkPointsAndUpdateBadge($utilisateur);
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
        $currentBadge = $utilisateur->getBadge();

        // Récupère tous les badges triés par niveau croissant
        $badges = $this->em->getRepository(Badge::class)->findBy([], ['id' => 'ASC']);

        $newBadge = null;

        for ($i = 0; $i < count($badges); $i++) {
            $badge = $badges[$i];
            $requiredPoints = 25 * ($i);

            if ($points >= $requiredPoints) {
                $newBadge = $badge;
            } else {
                break;
            }
        }

        if ($newBadge && $currentBadge->getId() !== $newBadge->getId()) {
            $utilisateur->setBadge($newBadge);
            $this->em->persist($utilisateur);
            $this->em->flush();
        }
    }

}
