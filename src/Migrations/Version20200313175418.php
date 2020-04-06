<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313175418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery CHANGE location title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE images DROP INDEX UNIQ_E01FBE6A93CB796C, ADD INDEX IDX_E01FBE6A93CB796C (file_id)');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A4E7AF8F');
        $this->addSql('DROP INDEX IDX_E01FBE6A4E7AF8F ON images');
        $this->addSql('ALTER TABLE images DROP gallery_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery CHANGE title location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE images DROP INDEX IDX_E01FBE6A93CB796C, ADD UNIQUE INDEX UNIQ_E01FBE6A93CB796C (file_id)');
        $this->addSql('ALTER TABLE images ADD gallery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A4E7AF8F ON images (gallery_id)');
    }
}
