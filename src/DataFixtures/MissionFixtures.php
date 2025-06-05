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
                'titre' => 'Recensement des Axolotls en milieu naturel',
                'description' => 'Étude et protection des populations d\'Axolotl dans les zones humides protégées.',
                'descriptionCourte' => 'Axolotl à l\'état sauvage',
                'dateAjout' => new \DateTime('2024-03-10'),
                'dateDebut' => new \DateTime('2024-04-01'),
                'dateFin' => new \DateTime('2024-04-10'),
                'utilisateurRef' => 'utilisateur_0',
                'photoRefs' => ['photo_0', 'photo_3'],
            ],
            [
                'titre' => 'Observation des Méduses immortelles en Méditerranée',
                'description' => 'Suivi des populations de méduse immortelle et étude de leur cycle de vie unique.',
                'descriptionCourte' => 'Méduse immortelle',
                'dateAjout' => new \DateTime('2024-05-05'),
                'dateDebut' => new \DateTime('2024-05-15'),
                'dateFin' => new \DateTime('2024-05-20'),
                'utilisateurRef' => 'utilisateur_0',
                'photoRefs' => ['photo_6', 'photo_7'],
            ],
            [
                'titre' => 'Étude du Poisson-lune dans l\'océan Atlantique',
                'description' => 'Collecte de données sur la migration et la reproduction du Poisson-lune.',
                'descriptionCourte' => 'Poisson-lune Atlantique',
                'dateAjout' => new \DateTime('2024-06-10'),
                'dateDebut' => new \DateTime('2024-06-20'),
                'dateFin' => new \DateTime('2024-06-30'),
                'utilisateurRef' => 'utilisateur_1',
                'photoRefs' => ['photo_11'],
            ],
            [
                'titre' => 'Étude écologique du Crabe Yeti en Antarctique',
                'description' => 'Analyse des populations et comportement du Crabe Yeti dans les fonds marins.',
                'descriptionCourte' => 'Crabe Yeti Antarctique',
                'dateAjout' => new \DateTime('2024-07-01'),
                'dateDebut' => new \DateTime('2024-07-10'),
                'dateFin' => new \DateTime('2024-07-20'),
                'utilisateurRef' => 'utilisateur_0',
                'photoRefs' => ['photo_17', 'photo_16'],
            ],
            [
                'titre' => 'Suivi des Hippocampes pygmées dans les récifs coralliens',
                'description' => 'Observation des habitats et comportements des hippocampes pygmées.',
                'descriptionCourte' => 'Hippocampe pygmée récif',
                'dateAjout' => new \DateTime('2024-07-15'),
                'dateDebut' => new \DateTime('2024-07-25'),
                'dateFin' => new \DateTime('2024-08-05'),
                'utilisateurRef' => 'utilisateur_1',
                'photoRefs' => ['photo_22'],
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
