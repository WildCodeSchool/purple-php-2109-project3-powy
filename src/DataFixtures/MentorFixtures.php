<?php

namespace App\DataFixtures;

use App\Entity\Mentor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MentorFixtures extends Fixture implements DependentFixtureInterface
{
    public const USERSNUMBERFIXTURES = 10;

    public function load(ObjectManager $manager): void
    {
        //create 10 students for sectormatch testing
        for ($i = 0; $i < self::USERSNUMBERFIXTURES; $i++) {
            $mentor = new Mentor();
            $mentor->setJobTitle('job_' . $i, $mentor);
            $mentor->setCareerDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
            ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in 
            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
            $mentor->setProfessionalSector($this->getReference('sector_13'));
            $mentor->setCompany($this->getReference('company_3'));
            $this->addReference('mentor_' . $i, $mentor);
            $manager->persist($mentor);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProfessionalSectorFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
