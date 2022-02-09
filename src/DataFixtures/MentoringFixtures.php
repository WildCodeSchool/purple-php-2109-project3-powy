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
    public const EXPIREDMENTORINGSFIXTURES = 3;

    public function load(ObjectManager $manager): void
    {

        //set student 0 to 4 with an accepted mentoring
        for ($i = 0; $i < self::MENTORINGFIXTURES; $i++) {
            $mentoring = new Mentoring();
            $mentoring->setStartingDate(new DateTime('2022-01-20'));
            $mentoring->setEndingDtae(new DateTime('2022-05-20'));
            $mentoring->setStudent($this->getReference('student_' . $i));
            $mentoring->setMentor($this->getReference('mentor_' . $i));
            $mentoring->setMentoringTopic(3);
            $mentoring->setIsAccepted(true);
            $manager->persist($mentoring);
        }
        //set a pending mentoring
        $mentoring = new Mentoring();
        $mentoring->setStudent($this->getReference('student_5'));
        $mentoring->setMentor($this->getReference('mentor_5'));
        $mentoring->setMentoringTopic(3);
        $mentoring->setIsAccepted(null);
        $manager->persist($mentoring);

       //set 3 mentorings with an expired date for student 6 et mentor 6
        for ($i = 0; $i < self::EXPIREDMENTORINGSFIXTURES; $i++) {
            $mentoring = new Mentoring();
            $mentoring->setStartingDate(
                new DateTime('2021-01-20')
            );
            $mentoring->setEndingDtae(
                new DateTime('2021-05-20')
            );
            $mentoring->setStudent($this->getReference('student_6'));
            $mentoring->setMentor($this->getReference('mentor_6'));
            $mentoring->setMentoringTopic($i + 1);
            $mentoring->setIsAccepted(true);
            $manager->persist($mentoring);
        }

/*         //set 3 mentorings with an expired date for student 7 et mentor 7
        for ($i = 0; $i < self::EXPIREDMENTORINGSFIXTURES; $i++) {
            $mentoring = new Mentoring();
            $mentoring->setStartingDate(
                new DateTime('2021-01-20')
            );
            $mentoring->setEndingDtae(
                new DateTime('2021-05-20')
            );
            $mentoring->setStudent($this->getReference('student_7'));
            $mentoring->setMentor($this->getReference('mentor_7'));
            $mentoring->setMentoringTopic($i + 1);
            $mentoring->setIsAccepted(true);
            $manager->persist($mentoring);
        } */

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
