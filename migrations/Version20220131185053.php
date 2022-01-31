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
        // this down() migration is auto-generated, please modify it to your needs

    }
}
