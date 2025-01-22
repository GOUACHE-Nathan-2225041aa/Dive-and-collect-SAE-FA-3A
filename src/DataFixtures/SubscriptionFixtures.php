<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class SubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $types = ['standard', 'medium', 'premium'];

        $features = [
            ['Feature 1', 'Feature 2', 'Feature 3'],
            ['Standard', 'Feature 4', 'Feature 5'],
            ['Medium', 'Feature 6', 'Feature 7'],
        ];

        for ($i = 0; $i < 3; $i++) {
            $subscription = new Subscription();
            $subscription->setType($types[$i])
                ->setPricePerMonth($faker->randomFloat(2, 10, 40))
                ->setAllowedFeatures($features[$i]);

            $manager->persist($subscription);

            $this->addReference('subscription_' . $types[$i], $subscription);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
