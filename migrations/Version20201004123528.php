<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004123528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE heading_pages (id INT AUTO_INCREMENT NOT NULL, id_heading_id INT NOT NULL, title_page VARCHAR(255) NOT NULL, content_page LONGTEXT NOT NULL, INDEX IDX_C21DE60CC774E43B (id_heading_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE headings (id INT AUTO_INCREMENT NOT NULL, title_heading VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heading_pages ADD CONSTRAINT FK_C21DE60CC774E43B FOREIGN KEY (id_heading_id) REFERENCES headings (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_pages DROP FOREIGN KEY FK_C21DE60CC774E43B');
        $this->addSql('DROP TABLE heading_pages');
        $this->addSql('DROP TABLE headings');
    }
}
