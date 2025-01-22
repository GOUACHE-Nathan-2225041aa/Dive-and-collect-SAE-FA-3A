<?php

namespace App\DataFixtures;

use App\Entity\Spot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpotFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $premiumSpot = new Spot();
        $premiumSpot->setName("Les Gorges du Verdon")
            ->setLatitude(43.794048610255000)
            ->setLongitude(6.2128934447579)
            ->setImage("1-gip-plane-riou-patrick-guzik-convention-319e8602803eca9ba282904146ad61e36195255f.webp")
            ->setIsPremium(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setMarineEnvironment(true);

        $manager->persist($premiumSpot);

        $spotOne = new Spot();
        $spotOne->setName("Spot du Lion de Mer")
            ->setLatitude(43.407774955790000)
            ->setLongitude(6.774146039234900)
            ->setImage("liondemer-c7a5b75576ca8b2a1ed07f4e397673bb90b2859e.webp")
            ->setIsPremium(false)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setMarineEnvironment(true);

        $manager->persist($spotOne);
        $this->addReference('spot_1', $spotOne);

        $spotTwo = new Spot();
        $spotTwo->setName("Archipel du Riou")
            ->setLatitude(43.179418273033000)
            ->setLongitude(5.387309850049600)
            ->setImage("1-gip-plane-riou-patrick-guzik-convention-319e8602803eca9ba282904146ad61e36195255f.webp")
            ->setIsPremium(false)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setMarineEnvironment(true);

        $manager->persist($spotTwo);
        $this->addReference('spot_2', $spotTwo);

        $manager->flush();
    }
}
