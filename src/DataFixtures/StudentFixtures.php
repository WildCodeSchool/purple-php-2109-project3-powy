<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //create one student for Student Entity testing
        $student = new Student();
        $student->setScholarship(false);
        $student->setDreamJob("Développeur");
        $student->setDreamDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in 
        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
        $student->setUser($this->getReference('student_1'));
        $student->setProfessionalSector($this->getReference('sector_13'));
        $student->setSchool($this->getReference('school_3'));
        $student->setStudyLevel($this->getReference('study_2'));
        $manager->persist($student);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProfessionalSectorFixtures::class,
            SchoolFixtures::class,
            StudyLevelFixtures::class,
        ];
    }
}
