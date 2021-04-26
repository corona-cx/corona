<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210426065451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, population INT NOT NULL, nuts VARCHAR(16) DEFAULT NULL, population_federal_state INT NOT NULL, federal_state VARCHAR(255) NOT NULL, county VARCHAR(255) NOT NULL, shape LONGTEXT NOT NULL, object_id INT NOT NULL, adm_unit_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data (id INT AUTO_INCREMENT NOT NULL, area_id INT NOT NULL, date_time DATETIME NOT NULL, death_rate DOUBLE PRECISION DEFAULT NULL, cases INT DEFAULT NULL, deaths INT DEFAULT NULL, cases_per100k DOUBLE PRECISION DEFAULT NULL, cases_per_population DOUBLE PRECISION DEFAULT NULL, cases7_per100k DOUBLE PRECISION DEFAULT NULL, recovered INT DEFAULT NULL, cases7_bl_per100_k DOUBLE PRECISION DEFAULT NULL, cases7_bl INT DEFAULT NULL, death7_bl INT DEFAULT NULL, cases7_lk INT DEFAULT NULL, death7_lk INT DEFAULT NULL, INDEX IDX_ADF3F363BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data ADD CONSTRAINT FK_ADF3F363BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data DROP FOREIGN KEY FK_ADF3F363BD0F409C');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE data');
    }
}
