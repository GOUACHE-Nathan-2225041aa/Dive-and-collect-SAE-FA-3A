<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\ONG;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ONGFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $ongData = [
            ['email' => 'oceansave@example.com', 'nomOng' => 'Ocean Save', 'prenomContact' => 'Marie', 'points' => 0],
            ['email' => 'forestguard@example.com', 'nomOng' => 'Forest Guard', 'prenomContact' => 'Lucas','points' => 20],
            ['email' => 'planetcare@example.com', 'nomOng' => 'Planet Care', 'prenomContact' => 'Sophie','points' => 150],
        ];

        foreach ($ongData as $data) {
            $ong = new ONG();
            $ong->setEmail($data['email']);
            $ong->setNomOng($data['nomOng']);
            $ong->setPrenomContact($data['prenomContact']);
            $ong->setRoles(['ROLE_ONG']);
            $ong->setPassword(
                $this->passwordHasher->hashPassword($ong, 'password') // mot de passe simple
            );
            $ong->setPoints($data['points']);

            $manager->persist($ong);
        }

//        $manager->flush();

        $BadgeData = [
            ['nom' => 'Badge 1', 'description' => 'description badge 1'],
            ['nom' => 'Badge 2', 'description' => 'description badge 2'],
            ['nom' => 'Badge 3', 'description' => 'description badge 3'],
        ];

        foreach ($BadgeData as $data) {
            $badge = new Badge();
            $badge->setNom($data['nom']);
            $badge->setDescription($data['description']);

            $manager->persist($badge);
        }

        $manager->flush();
    }
}