<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202175336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_requests (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(50) NOT NULL, message_title VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heading_pages (id INT AUTO_INCREMENT NOT NULL, heading_id INT NOT NULL, redactor_id INT NOT NULL, title_page VARCHAR(255) NOT NULL, content_page LONGTEXT NOT NULL, modification_date DATETIME NOT NULL, INDEX IDX_C21DE60C62FE64EC (heading_id), INDEX IDX_C21DE60C8E706861 (redactor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE headings (id INT AUTO_INCREMENT NOT NULL, title_heading VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F76F891240C806B8 (title_heading), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, newsletter_subscriber TINYINT(1) NOT NULL, change_password TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heading_pages ADD CONSTRAINT FK_C21DE60C62FE64EC FOREIGN KEY (heading_id) REFERENCES headings (id)');
        $this->addSql('ALTER TABLE heading_pages ADD CONSTRAINT FK_C21DE60C8E706861 FOREIGN KEY (redactor_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_pages DROP FOREIGN KEY FK_C21DE60C62FE64EC');
        $this->addSql('ALTER TABLE heading_pages DROP FOREIGN KEY FK_C21DE60C8E706861');
        $this->addSql('DROP TABLE contact_requests');
        $this->addSql('DROP TABLE heading_pages');
        $this->addSql('DROP TABLE headings');
        $this->addSql('DROP TABLE users');
    }
}
