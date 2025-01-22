<?php

namespace App\DataFixtures;

use App\Entity\UserSubscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserSubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // List of user and subscription references
        $users = ['user_3', 'user_4', 'user_5'];
        $subscriptions = ['subscription_standard', 'subscription_medium', 'subscription_premium'];

        // Associate each user with a subscription
        foreach ($users as $index => $userReference) {
            $userSubscription = new UserSubscription();
            $userSubscription->setUser($this->getReference($userReference))
                ->setSubscription($this->getReference($subscriptions[$index]))
                ->setStartDate(new \DateTime())
                ->setEndDate((new \DateTime())->modify('+1 month'))
                ->setActive(true);

            $manager->persist($userSubscription);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SubscriptionFixtures::class,
        ];
    }
}
