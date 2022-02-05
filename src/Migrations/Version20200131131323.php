<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200131131323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, zip_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B02347D662686 (zip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zip (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_421D9546F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinaries (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, google_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4936B1D8E7927C74 (email), INDEX IDX_4936B1D8979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eye_color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interventions (id INT AUTO_INCREMENT NOT NULL, sheet_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_5ADBAD7F8B1206A5 (sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sheets (id INT AUTO_INCREMENT NOT NULL, weight INT DEFAULT NULL, size INT DEFAULT NULL, treatments LONGTEXT DEFAULT NULL, allergies LONGTEXT DEFAULT NULL, vaccines LONGTEXT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, last_visit DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animales (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, color_id INT DEFAULT NULL, user_id INT DEFAULT NULL, eye_color_id INT DEFAULT NULL, veterinary_id INT DEFAULT NULL, sheet_id INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, race VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, sterillised TINYINT(1) DEFAULT NULL, particularities LONGTEXT DEFAULT NULL, INDEX IDX_FF62B8DCC54C8C93 (type_id), INDEX IDX_FF62B8DC7ADA1FB5 (color_id), INDEX IDX_FF62B8DCA76ED395 (user_id), INDEX IDX_FF62B8DCCB96304E (eye_color_id), INDEX IDX_FF62B8DCD954EB99 (veterinary_id), INDEX IDX_FF62B8DC8B1206A5 (sheet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, address VARCHAR(255) NOT NULL, address2 VARCHAR(255) DEFAULT NULL, INDEX IDX_D4E6F818BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, boss_name VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, siret VARCHAR(255) NOT NULL, INDEX IDX_8244AA3AF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B02347D662686 FOREIGN KEY (zip_id) REFERENCES zip (id)');
        $this->addSql('ALTER TABLE zip ADD CONSTRAINT FK_421D9546F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE veterinaries ADD CONSTRAINT FK_4936B1D8979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F8B1206A5 FOREIGN KEY (sheet_id) REFERENCES sheets (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE animales ADD CONSTRAINT FK_FF62B8DCC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE animales ADD CONSTRAINT FK_FF62B8DC7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE animales ADD CONSTRAINT FK_FF62B8DCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE animales ADD CONSTRAINT FK_FF62B8DCCB96304E FOREIGN KEY (eye_color_id) REFERENCES eye_color (id)');
        $this->addSql('ALTER TABLE animales ADD CONSTRAINT FK_FF62B8DCD954EB99 FOREIGN KEY (veterinary_id) REFERENCES veterinaries (id)');
        $this->addSql('ALTER TABLE animales ADD CONSTRAINT FK_FF62B8DC8B1206A5 FOREIGN KEY (sheet_id) REFERENCES sheets (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F818BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3AF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F818BAC62AF');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B02347D662686');
        $this->addSql('ALTER TABLE animales DROP FOREIGN KEY FK_FF62B8DCD954EB99');
        $this->addSql('ALTER TABLE animales DROP FOREIGN KEY FK_FF62B8DCCB96304E');
        $this->addSql('ALTER TABLE animales DROP FOREIGN KEY FK_FF62B8DC7ADA1FB5');
        $this->addSql('ALTER TABLE animales DROP FOREIGN KEY FK_FF62B8DCA76ED395');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F8B1206A5');
        $this->addSql('ALTER TABLE animales DROP FOREIGN KEY FK_FF62B8DC8B1206A5');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9F5B7AF75');
        $this->addSql('ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3AF5B7AF75');
        $this->addSql('ALTER TABLE veterinaries DROP FOREIGN KEY FK_4936B1D8979B1AD6');
        $this->addSql('ALTER TABLE animales DROP FOREIGN KEY FK_FF62B8DCC54C8C93');
        $this->addSql('ALTER TABLE zip DROP FOREIGN KEY FK_421D9546F92F3E70');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE zip');
        $this->addSql('DROP TABLE veterinaries');
        $this->addSql('DROP TABLE eye_color');
        $this->addSql('DROP TABLE interventions');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE sheets');
        $this->addSql('DROP TABLE animales');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE country');
    }
}
