<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902131129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competitor (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, email VARCHAR(250) NOT NULL, name VARCHAR(250) NOT NULL, surname VARCHAR(500) NOT NULL, position VARCHAR(12) NOT NULL, food_intolerances LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E0D53BAAE7927C74 (email), INDEX IDX_E0D53BAA8D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competitor ADD CONSTRAINT FK_E0D53BAA8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE competitor');
    }
}
