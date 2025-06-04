<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\Utilisateur;
use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MissionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Création manuelle de quelques missions
        $missionsData = [
            [
                'titre' => 'Recensement des coraux à Port-Cros',
                'description' => 'Inventaire des coraux présents autour de l’île de Port-Cros, zone protégée.',
                'descriptionCourte' => 'Coraux à Port-Cros',
                'dateAjout' => new \DateTime('2024-05-10'),
                'dateDebut' => new \DateTime('2024-05-20'),
                'dateFin' => new \DateTime('2024-05-25'),
                'utilisateurRef' => 'utilisateur_0',
                'photoRefs' => ['photo_0', 'photo_1'],
            ],
            [
                'titre' => 'Mission Alpha',
                'description' => 'Observation et recensement des poissons de la zone Alpha.',
                'descriptionCourte' => 'Exploration Alpha',
                'dateAjout' => new \DateTime('2024-06-01'),
                'dateDebut' => new \DateTime('2024-06-15'),
                'dateFin' => new \DateTime('2024-06-20'),
                'utilisateurRef' => 'utilisateur_1',
                'photoRefs' => ['photo_4', 'photo_1'],
            ],
            [
                'titre' => 'Suivi des mérous en Méditerranée',
                'description' => 'Étude des populations de mérous dans la réserve marine de Carry-le-Rouet.',
                'descriptionCourte' => 'Mérous à Carry',
                'dateAjout' => new \DateTime('2024-06-10'),
                'dateDebut' => new \DateTime('2024-06-21'),
                'dateFin' => new \DateTime('2024-06-27'),
                'utilisateurRef' => 'utilisateur_1',
                'photoRefs' => ['photo_2', 'photo_1'],
            ],
            [
                'titre' => 'Cartographie des herbiers de posidonie',
                'description' => 'Mise à jour des cartes des herbiers marins au large de Marseille.',
                'descriptionCourte' => 'Herbiers Marseille',
                'dateAjout' => new \DateTime('2024-06-15'),
                'dateDebut' => new \DateTime('2024-06-25'),
                'dateFin' => new \DateTime('2024-06-30'),
                'utilisateurRef' => 'utilisateur_1',
                'photoRefs' => ['photo_5', 'photo_0', 'photo_3'],
            ],
        ];


        foreach ($missionsData as $index => $data) {
            $mission = new Mission();
            $mission->setTitre($data['titre']);
            $mission->setDescription($data['description']);
            $mission->setDescriptionCourte($data['descriptionCourte']);
            $mission->setDateAjout($data['dateAjout']);
            $mission->setDateDebut($data['dateDebut']);
            $mission->setDateFin($data['dateFin']);

            /** @var Utilisateur $utilisateur */
            $utilisateur = $this->getReference($data['utilisateurRef'], Utilisateur::class);
            $mission->setUtilisateur($utilisateur);

            foreach ($data['photoRefs'] as $photoRef) {
                /** @var Photo $photo */
                $photo = $this->getReference($photoRef, Photo::class);
                $mission->addImage($photo);
            }

            $manager->persist($mission);

            // Ajoute une référence si besoin (utile pour d'autres fixtures)
            $this->addReference("mission_$index", $mission);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            PhotoFixtures::class,
        ];
    }
}
