<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\Utilisateur;
use App\Repository\BadgeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher, private BadgeRepository $badgeRepository)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $badgeData = [
            ['nom' => 'Badge 1', 'description' => 'description badge 1'],
            ['nom' => 'Badge 2', 'description' => 'description badge 2'],
            ['nom' => 'Badge 3', 'description' => 'description badge 3'],
        ];

        foreach ($badgeData as $data) {
            $badge = new Badge();
            $badge->setNom($data['nom']);
            $badge->setDescription($data['description']);

            $manager->persist($badge);
        }

        $manager->flush();

        $ongData = [
            ['email' => 'oceansave@example.com', 'nomOng' => 'Ocean Save', 'prenomContact' => 'Marie', 'points' => 0, 'badgeNoms' => ['Badge 1', 'Badge 2']],
            ['email' => 'forestguard@example.com', 'nomOng' => 'Forest Guard', 'prenomContact' => 'Lucas','points' => 20, 'badgeNoms' => ['Badge 2']],
            ['email' => 'planetcare@example.com', 'nomOng' => 'Planet Care', 'prenomContact' => 'Sophie','points' => 150, 'badgeNoms' => ['Badge 1', 'Badge 3', 'Badge 2']],
        ];

        foreach ($ongData as $data) {
            $ong = new Utilisateur();
            $ong->setEmail($data['email']);
            $ong->setNomOng($data['nomOng']);
            $ong->setUsername($data['prenomContact']);
            $ong->setRoles(['ROLE_ONG']);
            $ong->setPassword(
                $this->passwordHasher->hashPassword($ong, 'password') // mot de passe simple
            );
            $ong->setPoints($data['points']);
            // add badges
            foreach ($data['badgeNoms'] as $badgeNom) {
                $badge = $this->badgeRepository->findOneBy(['nom' => $badgeNom]);
                if ($badge) {
                    $ong->addBadge($badge);
                }
            }

            $manager->persist($ong);
        }

        $manager->flush();
    }
}
