<?php

namespace App\DataFixtures;

use App\Entity\Question;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($index = 1; $index <= 5; $index++) {
            $manager->persist(
                Question::create(
                    sprintf('MyPseudo %d', $index),
                    new DateTimeImmutable(),
                    'Bonjour, ceci est une question assez longue pour tester le système ?'
                )
            );
        }

        $manager->flush();
    }
}
