<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114091434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mentoring (id INT AUTO_INCREMENT NOT NULL, starting_date DATETIME DEFAULT NULL, ending_dtae DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentor ADD mentoring_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DECAFE00DA FOREIGN KEY (mentoring_id) REFERENCES mentoring (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_801562DECAFE00DA ON mentor (mentoring_id)');
        $this->addSql('ALTER TABLE student ADD mentoring_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33CAFE00DA FOREIGN KEY (mentoring_id) REFERENCES mentoring (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33CAFE00DA ON student (mentoring_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DECAFE00DA');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33CAFE00DA');
        $this->addSql('DROP TABLE mentoring');
        $this->addSql('DROP INDEX UNIQ_801562DECAFE00DA ON mentor');
        $this->addSql('ALTER TABLE mentor DROP mentoring_id');
        $this->addSql('DROP INDEX UNIQ_B723AF33CAFE00DA ON student');
        $this->addSql('ALTER TABLE student DROP mentoring_id');
    }
}
