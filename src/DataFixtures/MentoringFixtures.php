<?php

namespace App\DataFixtures;

use App\Entity\Mentoring;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MentoringFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $startingDate = new DateTime('2022-01-20');
        $mentoring = new Mentoring();
        $mentoring->setStartingDate($startingDate);
        $mentoring->setEndingDtae(new DateTime('2022-05-20'));
        $mentoring->setStudent($this->getReference('student_1'));
        $mentoring->setMentor($this->getReference('mentor_1'));
        $manager->persist($mentoring);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StudentFixtures::class,
            MentorFixtures::class,
        ];
    }
}
