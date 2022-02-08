<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220207151908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO user (email, roles, password, is_verified, firstname, lastname, age, phone, created_at) VALUES ("admin@powy.io", "[\"ROLE_ADMIN\"]", "$2y$13$OJgJF/jEDJ2pMq7Y5qDo0.G0KvCLGY0PLGEpLt2HTuWzCW1iFsSQy", true, "POWY", "ADMIN", 18, "0234567800", NOW() )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
