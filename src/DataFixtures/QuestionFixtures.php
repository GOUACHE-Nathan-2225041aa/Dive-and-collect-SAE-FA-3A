<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 8; $i++) {
            $question = new Question();
            $question->setTitle($faker->sentence(6, true) . ' ?')
            ->setAnswer($faker->paragraphs(rand(3, 5), true))
            ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($question);
        }

        $manager->flush();
    }
}
