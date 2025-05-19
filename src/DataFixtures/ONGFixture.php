<?php

namespace App\DataFixtures;

use App\Entity\ONG;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ONGFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $ongData = [
            ['email' => 'oceansave@example.com', 'nomOng' => 'Ocean Save', 'prenomContact' => 'Marie'],
            ['email' => 'forestguard@example.com', 'nomOng' => 'Forest Guard', 'prenomContact' => 'Lucas'],
            ['email' => 'planetcare@example.com', 'nomOng' => 'Planet Care', 'prenomContact' => 'Sophie'],
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

            $manager->persist($ong);
        }

        $manager->flush();
    }
}