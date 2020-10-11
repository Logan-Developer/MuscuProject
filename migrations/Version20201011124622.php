<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201011124622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_pages DROP FOREIGN KEY FK_C21DE60CC774E43B');
        $this->addSql('DROP INDEX IDX_C21DE60CC774E43B ON heading_pages');
        $this->addSql('ALTER TABLE heading_pages CHANGE id_heading_id heading_id INT NOT NULL');
        $this->addSql('ALTER TABLE heading_pages ADD CONSTRAINT FK_C21DE60C62FE64EC FOREIGN KEY (heading_id) REFERENCES headings (id)');
        $this->addSql('CREATE INDEX IDX_C21DE60C62FE64EC ON heading_pages (heading_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_pages DROP FOREIGN KEY FK_C21DE60C62FE64EC');
        $this->addSql('DROP INDEX IDX_C21DE60C62FE64EC ON heading_pages');
        $this->addSql('ALTER TABLE heading_pages CHANGE heading_id id_heading_id INT NOT NULL');
        $this->addSql('ALTER TABLE heading_pages ADD CONSTRAINT FK_C21DE60CC774E43B FOREIGN KEY (id_heading_id) REFERENCES headings (id)');
        $this->addSql('CREATE INDEX IDX_C21DE60CC774E43B ON heading_pages (id_heading_id)');
    }
}
