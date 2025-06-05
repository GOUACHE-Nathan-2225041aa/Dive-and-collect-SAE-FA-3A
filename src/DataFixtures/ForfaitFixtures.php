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

        // On flush ici pour que les lots soient enregistrés
        $manager->flush();

        // Créer les forfaits en liant par nom les lots
        $forfaitData = [
            [
                'nom' => 'FORFAIT DE BASE',
                'role' => 'FORFAIT_ONG_BASE',
                'description' => 'description forfait de base',
                'lotNoms' => ['LOT MIN']
            ],
            [
                'nom' => 'FORFAIT PREMIUM',
                'role' => 'FORFAIT_ONG_PREMIUM',
                'description' => 'description forfait premium',
                'lotNoms' => ['LOT MIN', 'LOT MED']
            ],
            [
                'nom' => 'FORFAIT PERSONNALISE',
                'role' => 'FORFAIT_ONG_PERSO',
                'description' => 'description forfait personnalisé',
                'lotNoms' => ['LOT MIN', 'LOT MED', 'LOT MAXI']
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