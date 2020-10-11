<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004141631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C21DE60C5FD06C5F ON heading_pages (title_page)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F76F891240C806B8 ON headings (title_heading)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C21DE60C5FD06C5F ON heading_pages');
        $this->addSql('DROP INDEX UNIQ_F76F891240C806B8 ON headings');
    }
}
