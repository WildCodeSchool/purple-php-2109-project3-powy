<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    private const COMPANIES = [
        'Company1',
        'Company2',
        'Company3',
        'Company4',
        'Company5',
        'Autre',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::COMPANIES as $key => $companyName) {
            $company = new Company();
            $company->setName($companyName);
            $manager->persist($company);
            $this->addReference('company_' . $key, $company);
        }
        $manager->flush();
    }
}
