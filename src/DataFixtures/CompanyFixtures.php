<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const COMPANIESNUMBER = 20;
    public function load(ObjectManager $manager): void
    {
        //creation of 20 compagnies
        for ($i = 0; $i < self::COMPANIESNUMBER; $i++) {
            $company = new Company();
            $company->setName('company_' . $i);
            $this->addReference('company_' . $i, $company);
            $manager->persist($company);
        }
        $manager->flush();
    }
}
