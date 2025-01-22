<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $adminAccountOne = new User();
        $adminAccountOne->setFirstname('Lola')
            ->setLastname('Vilatte')
            ->setEmail('lolavilatte@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($adminAccountOne, 'Azerty1234'))
            ->setProfession('Developer')
            ->setLocation('Aubagne')
            ->setBiography('Developer based in Aubagne.')
            ->setRoles(['ROLE_ADMIN', 'ROLE_DIVER', 'ROLE_USER'])
            ->setCertificate('Cert123.webp')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setVerified(true)
            ->setCertificateIsValidate(true);

        $manager->persist($adminAccountOne);
        $this->addReference('user_1', $adminAccountOne);

        $adminAccountTwo = new User();
        $adminAccountTwo->setFirstname('Melwin')
            ->setLastname('Duquenne')
            ->setEmail('melwinduquenne@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($adminAccountTwo, 'Azerty1234'))
            ->setProfession('Developer')
            ->setLocation('Aubagne')
            ->setBiography('Developer based in Aubagne.')
            ->setRoles(['ROLE_ADMIN', 'ROLE_DIVER', 'ROLE_USER'])
            ->setCertificate('Cert456.webp')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setVerified(true)
            ->setCertificateIsValidate(true);

        $manager->persist($adminAccountTwo);
        $this->addReference('user_2', $adminAccountTwo);

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail($faker->unique()->safeEmail())
                ->setPassword($this->passwordHasher->hashPassword($user, 'Password00'))
                ->setProfession($faker->jobTitle())
                ->setLocation($faker->city())
                ->setBiography($faker->text())
                ->setRoles(['ROLE_USER'])
                ->setCertificate($faker->word())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setVerified($faker->boolean())
                ->setCertificateIsValidate($faker->boolean());

            if ($user->isCertificateIsValidate()) {
                $user->setRoles(array_merge($user->getRoles(), ['ROLE_DIVER']));
            }

            $manager->persist($user);
            $this->addReference('user_' . ($i + 3), $user);
        }
        $manager->flush();
    }
}
