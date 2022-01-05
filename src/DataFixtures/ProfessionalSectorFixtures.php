<?php

namespace App\DataFixtures;

use App\Entity\ProfessionalSector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfessionalSectorFixtures extends Fixture
{
    private const PROFESSIONALSECTORS = [
        'Art, Design',
        'Audiovisuel, Spectacle, Cinéma',
        'Audit, Conseil, Expertise',
        'Automobile',
        'Banque, Assurance',
        'BTP, architecture',
        'Chimie, pharmacie',
        'Commerce, distribution, e-commerce',
        'Construction aéronautique, ferroviaire et navale',
        'Culture, Artisanat d\'art',
        'Droit, justice',
        'Environnement',
        'Habillement, Mode',
        'Hôtellerie, Restauration, Tourisme',
        'Informatique, Numérique et Réseaux',
        'Logistique, transport',
        'Maintenance, entretien',
        'Marketing, publicité, Communication',
        'Matériaux, Transformations',
        'Mécanique',
        'Santé, médical',
        'Social, Services à la personne',
        'Sport et loisirs',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROFESSIONALSECTORS as $key => $sectorName) {
            $sector = new ProfessionalSector();
            $sector->setName($sectorName);
            $manager->persist($sector);
            $this->addReference('sector_' . $key, $sector);
        }
        $manager->flush();
    }
}
