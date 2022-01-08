<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220108140754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mentoring (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, mentor_id INT DEFAULT NULL, starting_date DATETIME NOT NULL, ending_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_2DEE6E1BCB944F1A (student_id), UNIQUE INDEX UNIQ_2DEE6E1BDB403044 (mentor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentoring ADD CONSTRAINT FK_2DEE6E1BCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE mentoring ADD CONSTRAINT FK_2DEE6E1BDB403044 FOREIGN KEY (mentor_id) REFERENCES mentor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mentoring');
    }
}
