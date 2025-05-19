<?php

namespace App\DataFixtures;

use App\Entity\Coordonnee;
use App\Entity\EspecePoisson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EspecePoissonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $poisson = new EspecePoisson();
            $poisson->setNom("Poisson #$i");

            // Ajout de 2-3 coordonnées fictives
            for ($j = 0; $j < rand(1, 3); $j++) {
                $coord = new Coordonnee();
                $coord->setLatitude(mt_rand(-9000, 9000) / 100);
                $coord->setLongitude(mt_rand(-18000, 18000) / 100);

                $manager->persist($coord);

                $poisson->addCoordonnee($coord); // méthode générée par la relation ManyToMany
            }

            $manager->persist($poisson);
        }

        $manager->flush();
    }
}
