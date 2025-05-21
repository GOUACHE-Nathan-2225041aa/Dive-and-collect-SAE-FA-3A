<?php

namespace App\DataFixtures;

use App\Entity\Forfait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ForfaitFixtures extends Fixture
{

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $forfaitData = [
            ['nom' => 'FORFAIT DE BASE', 'role' => 'FORFAIT_ONG_BASE', 'description' => 'description forfait de base'],
            ['nom' => 'FORFAIT PREMIUM', 'role' => 'FORFAIT_ONG_PREMIUM', 'description' => 'description forfait premium'],
            ['nom' => 'FORFAIT PERSONNALISE', 'role' => 'FORFAIT_ONG_PERSO', 'description' => 'description forfait personalisé'],
        ];

        foreach ($forfaitData as $data) {
            $forfait = new Forfait();
            $forfait->setNom($data['nom']);
            $forfait->setRole($data['role']);
            $forfait->setDescription($data['description']);

            $manager->persist($forfait);
        }

        $manager->flush();
    }
}