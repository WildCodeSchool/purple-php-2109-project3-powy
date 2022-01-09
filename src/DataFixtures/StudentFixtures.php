<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{

    public const USERSNUMBERFIXTURES = 10;

    public function load(ObjectManager $manager): void
    {
       //create 10 students for sectormatch testing
        for ($i = 0; $i < self::USERSNUMBERFIXTURES; $i++) {
            $student = new Student();
            $student->setScholarship(false);
            $student->setDreamJob("Dreamjob" . $i);
            $student->setDreamDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
            ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in 
            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
            $student->setProfessionalSector($this->getReference('sector_13'));
            $student->setSchool($this->getReference('school_3'));
            $student->setStudyLevel($this->getReference('study_2'));
            $this->addReference('student_' . $i, $student);
            $manager->persist($student);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProfessionalSectorFixtures::class,
            SchoolFixtures::class,
            StudyLevelFixtures::class,
        ];
    }
}
