<?php

namespace App\DataFixtures;

use App\Entity\Mentoring;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MentoringFixtures extends Fixture implements DependentFixtureInterface
{
    public const MATCHDURATION = 4;

    public function load(ObjectManager $manager)
    {
        /**
         * 2 mentoring relationships, with different starting dates
         * 8 students and 8 mentors available with same professionnal sector
         */

        //mentoring relationship 1
        $startingDate = new DateTime('2022-01-01 9:30:0');

        $endingDate = clone $startingDate;
        $endingDate = $endingDate->modify('+' . self::MATCHDURATION . ' months');

        $mentoring = new Mentoring();
        $mentoring->setStartingDate($startingDate);
        $mentoring->setEndingDate($endingDate);
        $mentoring->setStudent($this->getReference("student_0"));
        $mentoring->setMentor($this->getReference("mentor_0"));
        $manager->persist($mentoring);

        //mentoring relationship 2
        $startingDate = new DateTime('2022-01-06 12:30:0');

        $endingDate = clone $startingDate;
        $endingDate = $endingDate->modify('+' . self::MATCHDURATION . ' months');

        $mentoring = new Mentoring();
        $mentoring->setStartingDate($startingDate);
        $mentoring->setEndingDate($endingDate);
        $mentoring->setStudent($this->getReference("student_1"));
        $mentoring->setMentor($this->getReference("mentor_1"));
        $manager->persist($mentoring);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StudentFixtures::class,
            MentorFixtures::class
        ];
    }
}
