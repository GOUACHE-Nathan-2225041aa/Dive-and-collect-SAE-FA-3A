<?php

namespace App\DataFixtures;

use App\Entity\Forfait;
use App\Entity\LotDeDonnees;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LotDeDonneesFixtures extends Fixture
{

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $lotData = [
            ['nom' => 'LOT MIN', 'prix' => 49.99 , 'description' => 'description lot min'],
            ['nom' => 'LOT MED', 'prix' => 99.99, 'description' => 'description lot med'],
            ['nom' => 'LOT MAXI', 'prix' => 149.99, 'description' => 'description lot maxi'],
        ];

        foreach ($lotData as $data) {
            $lot = new LotDeDonnees();
            $lot->setNom($data['nom']);
            $lot->setDescription($data['description']);
            $lot->setPrix($data['prix']);

            $manager->persist($lot);
        }

        $manager->flush();
    }
}