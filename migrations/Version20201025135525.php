<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201025135525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_pages ADD redactor_id INT NOT NULL');
        $this->addSql('ALTER TABLE heading_pages ADD CONSTRAINT FK_C21DE60C8E706861 FOREIGN KEY (redactor_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_C21DE60C8E706861 ON heading_pages (redactor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_pages DROP FOREIGN KEY FK_C21DE60C8E706861');
        $this->addSql('DROP INDEX IDX_C21DE60C8E706861 ON heading_pages');
        $this->addSql('ALTER TABLE heading_pages DROP redactor_id');
    }
}
