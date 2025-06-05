<?php

namespace App\DataFixtures;

use App\Entity\Forfait;
use App\Entity\LotDeDonnees;
use App\Repository\LotDeDonneesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ForfaitFixtures extends Fixture
{

    private LotDeDonneesRepository $lotRepository;

    public function __construct(LotDeDonneesRepository $lotRepository)
    {
        $this->lotRepository = $lotRepository;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer les lots
        $lotData = [
            ['nom' => 'FEATURE MIN', 'prix' => 49.99 , 'description' => 'description feature min'],
            ['nom' => 'FEATURE MED', 'prix' => 99.99, 'description' => 'description feature med'],
            ['nom' => 'FEATURE MAXI', 'prix' => 149.99, 'description' => 'description feature maxi'],
        ];

        foreach ($lotData as $data) {
            $lot = new LotDeDonnees();
            $lot->setNom($data['nom']);
            $lot->setDescription($data['description']);
            $lot->setPrix($data['prix']);

            $manager->persist($lot);
        }

        // On flush ici pour que les lots soient enregistrés
        $manager->flush();

        // Créer les forfaits en liant par nom les lots
        $forfaitData = [
            [
                'nom' => 'BASIC PACKAGE',
                'role' => 'FORFAIT_ONG_BASE',
                'description' => 'description basic package',
                'lotNoms' => ['FEATURE MIN']
            ],
            [
                'nom' => 'PREMIUM PACKAGE',
                'role' => 'FORFAIT_ONG_PREMIUM',
                'description' => 'description premium package',
                'lotNoms' => ['FEATURE MIN', 'FEATURE MED']
            ],
            [
                'nom' => 'CUSTOM PACKAGE',
                'role' => 'FORFAIT_ONG_PERSO',
                'description' => 'description forfait personnalisé',
                'lotNoms' => ['FEATURE MIN', 'FEATURE MED', 'FEATURE MAXI']
            ],
        ];

        foreach ($forfaitData as $data) {
            $forfait = new Forfait();
            $forfait->setNom($data['nom']);
            $forfait->setRole($data['role']);
            $forfait->setDescription($data['description']);

            foreach ($data['lotNoms'] as $lotNom) {
                $lot = $this->lotRepository->findOneBy(['nom' => $lotNom]);
                if ($lot) {
                    $forfait->addLot($lot);
                }
            }

            $manager->persist($forfait);
        }

        $manager->flush();
    }
}