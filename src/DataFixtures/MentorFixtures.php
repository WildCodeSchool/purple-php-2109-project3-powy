<?php

namespace App\DataFixtures;

use App\Entity\Mentor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MentorFixtures extends Fixture implements DependentFixtureInterface
{
    public const AVAILABLEMENTORS = 10;

    public function load(ObjectManager $manager): void
    {
        /**
         * creation of 10 mentors with no active mentoring for match testing
         * with only one topic active (number 1 for all of them)
         * with same professionnal sector
         */
        for ($i = 0; $i < self::AVAILABLEMENTORS; $i++) {
            $mentor = new Mentor();
            $mentor->setTopic1(1);
            $mentor->setJobTitle("job_" . $i);
            $mentor->setCareerDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
            ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in 
            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
            //23 professional sectors set in ProfessionalSectorfixtures
            $mentor->setProfessionalSector($this->getReference('sector_13'));
            //20 companies set in CompanyFixtures
            $mentor->setCompany($this->getReference('company_' . $i));
            $this->addReference('mentor_' . $i, $mentor);
            $manager->persist($mentor);
        }

        //flush all students
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProfessionalSectorFixtures::class,
            CompanyFixtures::class
        ];
    }
}
