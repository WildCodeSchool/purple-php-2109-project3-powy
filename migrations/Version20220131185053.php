<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131185053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Art, design")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Audiovisuel, spectacle, cinéma")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Audit, conseil, expertise")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Automobile")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Banque, assurance")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("BTP, architecture")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Chimie, pharmacie")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Commerce, distribution, e-commerce")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Construction aéronautique, ferroviaire et navale")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Culture, artisanat d\'art")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Droit, justice")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Environnement")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Habillement, mode")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Hôtellerie, restauration, tourisme")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Informatique, numérique et réseaux")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Logistique, transport")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Maintenance, entretien")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Marketing, publicité, communication")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Matériaux, transformations")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Mécanique")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Santé, médical")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Social, services à la personne")');
        $this->addSql('INSERT INTO professional_sector (name) VALUES  ("Sport et loisirs")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("Lycée")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("CPGE")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("DUT")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("École POST-BAC")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("Licence Universitaire")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("Master Universitaire")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("Doctorat")');
        $this->addSql('INSERT INTO study_level (name) VALUES  ("Autre")');
        $this->addSql('INSERT INTO school (name) VALUES  ("Autre")');
        $this->addSql('INSERT INTO school (name) VALUES  ("CNED")');
        $this->addSql('INSERT INTO school (name) VALUES  ("CNAM")');
        $this->addSql('INSERT INTO school (name) VALUES  ("Pigier")');
        $this->addSql('INSERT INTO school (name) VALUES  ("Wild Code School")');
        $this->addSql('INSERT INTO company (name) VALUES  ("Autre")');
        $this->addSql('INSERT INTO company (name) VALUES  ("Wild Code School")');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM professional_sector WHERE name = "Art, design"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Audiovisuel, spectacle, cinéma"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Audit, conseil, expertise"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Automobile"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Banque, assurance"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "BTP, architecture"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Chimie, pharmacie"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Commerce, distribution, e-commerce"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Construction aéronautique, ferroviaire et navale"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Culture, artisanat d\'art")');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Droit, justice"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Environnement"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Habillement, mode"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Hôtellerie, restauration, tourisme"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Informatique, numérique et réseaux"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Logistique, transport"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Maintenance, entretien"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Marketing, publicité, communication"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Matériaux, transformations"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Mécanique"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Santé, médical"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Social, services à la personne"');
        $this->addSql('DELETE FROM professional_sector WHERE name = "Sport et loisirs"');
        $this->addSql('DELETE FROM study_level WHERE name = "Lycée"');
        $this->addSql('DELETE FROM study_level WHERE name = "CPGE"');
        $this->addSql('DELETE FROM study_level WHERE name = "DUT"');
        $this->addSql('DELETE FROM study_level WHERE name = "École POST-BAC"');
        $this->addSql('DELETE FROM study_level WHERE name = "Licence Universitaire"');
        $this->addSql('DELETE FROM study_level WHERE name = "Master Universitaire"');
        $this->addSql('DELETE FROM study_level WHERE name = "Doctorat"');
        $this->addSql('DELETE FROM study_level WHERE name = "Autre"');
        $this->addSql('DELETE FROM school WHERE name = "Autre"');
        $this->addSql('DELETE FROM school WHERE name = "CNED"');
        $this->addSql('DELETE FROM school WHERE name = "CNAM"');
        $this->addSql('DELETE FROM school WHERE name = "Pigier"');
        $this->addSql('DELETE FROM school WHERE name = "Wild Code School"');
        $this->addSql('DELETE FROM company WHERE name = "Autre"');
        $this->addSql('DELETE FROM company WHERE name = "Wild Code School"');
    }
}
