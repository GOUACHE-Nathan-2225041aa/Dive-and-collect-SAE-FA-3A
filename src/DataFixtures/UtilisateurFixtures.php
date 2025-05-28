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
            ['nom' => 'Badge 4', 'description' => 'description badge 4'],
        ];

        foreach ($badgeData as $data) {
            $badge = new Badge();
            $badge->setNom($data['nom']);
            $badge->setDescription($data['description']);

            $manager->persist($badge);
        }

        $manager->flush();

        $ongData = [
            ['email' => 'oceansave@example.com', 'nomOng' => 'Ocean Save', 'username' => 'Marie', 'points' => 0, 'badgeNoms' => ['Badge 1', 'Badge 2'], 'roles' => ['ROLE_ONG']],
            ['email' => 'forestguard@example.com', 'nomOng' => 'Forest Guard', 'username' => 'Lucas','points' => 20, 'badgeNoms' => ['Badge 2'],'roles' => ['ROLE_ONG']],
            ['email' => 'planetcare@example.com', 'nomOng' => 'Planet Care', 'username' => 'Sophie','points' => 150, 'badgeNoms' => ['Badge 1', 'Badge 3', 'Badge 2'],'roles' => ['ROLE_USER']],
            ['email' => 'admin@admin.com', 'nomOng' => '', 'username' => 'admin','points' => 10, 'badgeNoms' => ['Badge 1', 'Badge 3'],'roles' => ['ROLE_USER','ROLE_ADMIN']],
        ];

        foreach ($ongData as $data) {
            $ong = new Utilisateur();
            $ong->setEmail($data['email']);
            $ong->setNomOng($data['nomOng']);
            $ong->setUsername($data['username']);
            $ong->setRoles($data['roles']);
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
