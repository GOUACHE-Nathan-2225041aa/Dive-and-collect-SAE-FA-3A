<?php

namespace App\DataFixtures;

use App\Entity\Dive;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DiveFixtures extends Fixture implements DependentFixtureInterface
{
    private const SPOT_REFERENCES = [
        'spot_1',
        'spot_2',
    ];

    private const USER_REFERENCES = [
        'user_1',
        'user_2',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $spots = array_map(fn($ref) => $this->getReference($ref), self::SPOT_REFERENCES);
        $users = array_map(fn($ref) => $this->getReference($ref), self::USER_REFERENCES);

        for ($i = 0; $i < 10; $i++) {
            $dive = new Dive();
            $dive->setTitle($faker->sentence)
                ->setDescription($faker->paragraph)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setSpot($faker->randomElement($spots))
                ->setUser($faker->randomElement($users))
                ->setDate(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('2024-01-01', '2024-12-31')));

            $manager->persist($dive);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SpotFixtures::class,
            UserFixtures::class,
        ];
    }
}
