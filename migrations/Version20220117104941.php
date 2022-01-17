<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117104941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, topic1 INT NOT NULL, topic2 INT DEFAULT NULL, topic3 INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentor DROP topic2, DROP topic3, CHANGE topic1 topics_id INT NOT NULL');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DEBF06A414 FOREIGN KEY (topics_id) REFERENCES topic (id)');
        $this->addSql('CREATE INDEX IDX_801562DEBF06A414 ON mentor (topics_id)');
        $this->addSql('ALTER TABLE student DROP topic2, DROP topic3, CHANGE topic1 topics_id INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF06A414 FOREIGN KEY (topics_id) REFERENCES topic (id)');
        $this->addSql('CREATE INDEX IDX_B723AF33BF06A414 ON student (topics_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DEBF06A414');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF06A414');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP INDEX IDX_801562DEBF06A414 ON mentor');
        $this->addSql('ALTER TABLE mentor ADD topic2 INT DEFAULT NULL, ADD topic3 INT DEFAULT NULL, CHANGE topics_id topic1 INT NOT NULL');
        $this->addSql('DROP INDEX IDX_B723AF33BF06A414 ON student');
        $this->addSql('ALTER TABLE student ADD topic2 INT DEFAULT NULL, ADD topic3 INT DEFAULT NULL, CHANGE topics_id topic1 INT NOT NULL');
    }
}
