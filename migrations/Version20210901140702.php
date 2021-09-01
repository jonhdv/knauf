<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901140702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD email VARCHAR(250) NOT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(100) NOT NULL, ADD country VARCHAR(250) NOT NULL, ADD city VARCHAR(250) NOT NULL, ADD postal_code VARCHAR(250) NOT NULL, ADD contact_person VARCHAR(250) NOT NULL, ADD contact_person_phone VARCHAR(12) NOT NULL, ADD commentary TINYTEXT NOT NULL, ADD enabled TINYINT(1) NOT NULL, ADD updated_at DATETIME NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user DROP email, DROP roles, DROP password, DROP country, DROP city, DROP postal_code, DROP contact_person, DROP contact_person_phone, DROP commentary, DROP enabled, DROP updated_at, DROP created_at');
    }
}
