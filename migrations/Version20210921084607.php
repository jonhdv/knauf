<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921084607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cities (userId INT NOT NULL, cityId INT NOT NULL, INDEX IDX_BC0FAA5664B64DCC (userId), INDEX IDX_BC0FAA567F99FC72 (cityId), PRIMARY KEY(userId, cityId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_cities ADD CONSTRAINT FK_BC0FAA5664B64DCC FOREIGN KEY (userId) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cities ADD CONSTRAINT FK_BC0FAA567F99FC72 FOREIGN KEY (cityId) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE city city VARCHAR(250) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_cities DROP FOREIGN KEY FK_BC0FAA567F99FC72');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE user_cities');
        $this->addSql('ALTER TABLE user CHANGE city city VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
