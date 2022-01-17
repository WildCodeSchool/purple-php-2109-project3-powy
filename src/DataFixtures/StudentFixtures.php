<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{
    public const AVAILABLESTUDENTS = 10;

    public function load(ObjectManager $manager): void
    {
        /**
         * creation of 10 students with no active mentoring for match testing
         * with only one topic active (number 1 for all of them)
         * with same professionnal sector
         */
        for ($i = 0; $i < self::AVAILABLESTUDENTS; $i++) {
            $student = new Student();
            $student->setScholarship(false);
            $student->setDreamJob("Job_" . $i);
            $student->setDreamDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
            ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in 
            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
            $student->setProfessionalSector($this->getReference('sector_13'));
            $student->setSchool($this->getReference('school_3'));
            $student->setStudyLevel($this->getReference('study_2'));
            $student->setTopic($this->getReference('student_topic_' . $i));
            $this->addReference('student_' . $i, $student);
            $manager->persist($student);
        }

        //flush all students
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProfessionalSectorFixtures::class,
            SchoolFixtures::class,
            StudyLevelFixtures::class,
            TopicFixtures::class,
        ];
    }
}
