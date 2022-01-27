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
    public const MENTORINGFIXTURES = 5;

    public function load(ObjectManager $manager): void
    {
        //set student 0 to 4 with an accepted mentoring
        for ($i = 0; $i < self::MENTORINGFIXTURES; $i++) {
            $startingDate = new DateTime('2022-01-20');
            $mentoring = new Mentoring();
            $mentoring->setStartingDate($startingDate);
            $mentoring->setEndingDtae(new DateTime('2022-05-20'));
            $mentoring->setStudent($this->getReference('student_' . $i));
            $mentoring->setMentor($this->getReference('mentor_' . $i));
            $mentoring->setIsAccepted(true);
            $manager->persist($mentoring);
        }
        //set student with a pending mentoring
        $startingDate = new DateTime('2022-01-20');
        $mentoring = new Mentoring();
        $mentoring->setStartingDate($startingDate);
        $mentoring->setEndingDtae(new DateTime('2022-05-20'));
        $mentoring->setStudent($this->getReference('student_5'));
        $mentoring->setMentor($this->getReference('mentor_5'));
        $mentoring->setIsAccepted(false);
        $manager->persist($mentoring);

        //flush all user
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
