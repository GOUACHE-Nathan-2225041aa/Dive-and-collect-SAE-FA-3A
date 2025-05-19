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
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $ong = new ONG();

            $ong->setEmail("ong$i@example.com");
            $ong->setNomOng($faker->company());
            $ong->setPrenomContact($faker->firstName());
            $ong->setRoles(['ROLE_ONG']); // rôle personnalisé si tu veux
            $ong->setPassword(
                $this->passwordHasher->hashPassword($ong, 'password') // mot de passe par défaut
            );

            $manager->persist($ong);
        }

        $manager->flush();
    }
}