<?php

namespace App\DataFixtures;

use App\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SchoolFixtures extends Fixture
{
    private const SCHOOLS = [
        'Autre',
        'Ecole1',
        'Ecole2',
        'Ecole3',
        'Ecole4',
        'Ecole5'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SCHOOLS as $key => $schoolName) {
            $school = new School();
            $school->setName($schoolName);
            $manager->persist($school);
            $this->addReference('school_' . $key, $school);
        }
        $manager->flush();
    }
}
