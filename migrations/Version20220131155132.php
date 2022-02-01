<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131155132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DECAFE00DA');
        $this->addSql('DROP INDEX UNIQ_801562DECAFE00DA ON mentor');
        $this->addSql('ALTER TABLE mentor DROP mentoring_id');
        $this->addSql('ALTER TABLE mentoring ADD student_id INT DEFAULT NULL, ADD mentor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mentoring ADD CONSTRAINT FK_2DEE6E1BCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE mentoring ADD CONSTRAINT FK_2DEE6E1BDB403044 FOREIGN KEY (mentor_id) REFERENCES mentor (id)');
        $this->addSql('CREATE INDEX IDX_2DEE6E1BCB944F1A ON mentoring (student_id)');
        $this->addSql('CREATE INDEX IDX_2DEE6E1BDB403044 ON mentoring (mentor_id)');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33CAFE00DA');
        $this->addSql('DROP INDEX UNIQ_B723AF33CAFE00DA ON student');
        $this->addSql('ALTER TABLE student DROP mentoring_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mentor ADD mentoring_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DECAFE00DA FOREIGN KEY (mentoring_id) REFERENCES mentoring (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_801562DECAFE00DA ON mentor (mentoring_id)');
        $this->addSql('ALTER TABLE mentoring DROP FOREIGN KEY FK_2DEE6E1BCB944F1A');
        $this->addSql('ALTER TABLE mentoring DROP FOREIGN KEY FK_2DEE6E1BDB403044');
        $this->addSql('DROP INDEX IDX_2DEE6E1BCB944F1A ON mentoring');
        $this->addSql('DROP INDEX IDX_2DEE6E1BDB403044 ON mentoring');
        $this->addSql('ALTER TABLE mentoring DROP student_id, DROP mentor_id');
        $this->addSql('ALTER TABLE student ADD mentoring_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33CAFE00DA FOREIGN KEY (mentoring_id) REFERENCES mentoring (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33CAFE00DA ON student (mentoring_id)');
    }
}
