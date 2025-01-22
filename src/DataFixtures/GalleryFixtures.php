<?php

namespace App\DataFixtures;

use App\Entity\Dive;
use App\Entity\Gallery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GalleryFixtures extends Fixture implements DependentFixtureInterface
{
    private const GALLERY_NAMES = [
        'barracuda-mediterranée.webp',
        'fakarava-sud.webp',
        'iStock-1186735162.webp',
        'iStock-1420463556.webp',
        'soph6.jpg',
        'poisson-feuille-asie.jpg',
        'iStock-1304092978.jpg',
        'iStock-1422453670.jpg',
        '101.jpg',
        '121.jpg',
        '123.jpg',
        '456.jpg',
        '789.jpg',
        '242206946_10159496774257184_8277526167482384049_n.jpg',
        '5206249271.jpg',
        'Barracudas Mediterrannee.jpg',
        'Hervia Mediterranée.jpg',
        'iStock-458054417.jpg',
        'iStock-475982999.jpg',
        'iStock-620738106.jpg',
        'iStock-1179138818.jpg',
        'iStock-1388387984.jpg',
        'iStock-1422453266.jpg'
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $dives = $manager->getRepository(Dive::class)->findAll();
        if (count($dives) < 2) {
            throw new \Exception('Au moins deux plongées différentes sont requises');
        }

        for ($i = 0; $i < 23; $i++) {
            $gallery = new Gallery();
            $gallery->setName(self::GALLERY_NAMES[$i])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setDive($faker->randomElement($dives));

            $manager->persist($gallery);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DiveFixtures::class,
        ];
    }
}
