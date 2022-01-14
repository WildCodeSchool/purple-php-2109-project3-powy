<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105144057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mentor (id INT AUTO_INCREMENT NOT NULL, professional_sector_id INT NOT NULL, company_id INT NOT NULL, job_title VARCHAR(255) NOT NULL, career_description LONGTEXT DEFAULT NULL, INDEX IDX_801562DE6BD6818C (professional_sector_id), INDEX IDX_801562DE979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DE6BD6818C FOREIGN KEY (professional_sector_id) REFERENCES professional_sector (id)');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user ADD mentor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DB403044 FOREIGN KEY (mentor_id) REFERENCES mentor (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649DB403044 ON user (mentor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DE979B1AD6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DB403044');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE mentor');
        $this->addSql('DROP INDEX UNIQ_8D93D649DB403044 ON user');
        $this->addSql('ALTER TABLE user DROP mentor_id');
    }
}
