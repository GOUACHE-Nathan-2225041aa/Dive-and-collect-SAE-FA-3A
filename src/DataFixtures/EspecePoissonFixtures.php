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
        $nomsPoissons = [
            'Aloxotl',
            'Méduse immortelle',
            'Poisson-lune',
            'Crabe yeti',
            'Hippocampe pygmée',
            'Dauphin',
            'Narval'
        ];

        foreach ($nomsPoissons as $nom) {
            $poisson = new EspecePoisson();
            $poisson->setNom($nom);

            // Ajout de 2-3 coordonnées fictives
            for ($j = 0; $j < rand(1, 3); $j++) {
                $coord = new Coordonnee();
                $coord->setLatitude(mt_rand(-9000, 9000) / 100);
                $coord->setLongitude(mt_rand(-18000, 18000) / 100);

                $manager->persist($coord);
                $poisson->addCoordonnee($coord); // relation ManyToMany
            }

            $manager->persist($poisson);
        }

        $manager->flush();
    }
}
