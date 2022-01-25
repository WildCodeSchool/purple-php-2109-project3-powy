<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118132844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, topic1 INT NOT NULL, topic2 INT DEFAULT NULL, topic3 INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentor ADD topic_id INT DEFAULT NULL, DROP topic1, DROP topic2, DROP topic3');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DE1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_801562DE1F55203D ON mentor (topic_id)');
        $this->addSql('ALTER TABLE student ADD topic_id INT DEFAULT NULL, DROP topic1, DROP topic2, DROP topic3');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF331F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF331F55203D ON student (topic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DE1F55203D');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF331F55203D');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP INDEX UNIQ_801562DE1F55203D ON mentor');
        $this->addSql('ALTER TABLE mentor ADD topic1 INT NOT NULL, ADD topic3 INT DEFAULT NULL, CHANGE topic_id topic2 INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_B723AF331F55203D ON student');
        $this->addSql('ALTER TABLE student ADD topic1 INT NOT NULL, ADD topic3 INT DEFAULT NULL, CHANGE topic_id topic2 INT DEFAULT NULL');
    }
}
