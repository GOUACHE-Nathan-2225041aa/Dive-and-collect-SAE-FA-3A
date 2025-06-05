<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $badgeData = [
            ['nom' => 'Badge 1', 'description' => 'description badge 1', 'badgeFileName' => "poissonBadgeDefaut.png"],
            ['nom' => 'Badge 2', 'description' => 'description badge 2', 'badgeFileName' => "badgeNiv2.png"],
            ['nom' => 'Badge 3', 'description' => 'description badge 3', 'badgeFileName' => "badgeNiv3.png"]
        ];

        foreach ($badgeData as $data) {
            $badge = new Badge();
            $badge->setNom($data['nom']);
            $badge->setDescription($data['description']);
            $badge->setBadgeFileName($data["badgeFileName"]);

            $manager->persist($badge);
        }

        $manager->flush();
    }
}
