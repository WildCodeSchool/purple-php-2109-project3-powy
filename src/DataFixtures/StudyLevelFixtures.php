<?php

namespace App\DataFixtures;

use App\Entity\StudyLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudyLevelFixtures extends Fixture
{
    private const STUDYLEVELS = [
        'Brevet',
        'Bac',
        'Licence',
        'Autre',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STUDYLEVELS as $key => $studyName) {
            $study = new StudyLevel();
            $study->setName($studyName);
            $manager->persist($study);
            $this->addReference('study_' . $key, $study);
        }
        $manager->flush();
    }
}
