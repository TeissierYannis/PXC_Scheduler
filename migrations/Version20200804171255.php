<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804171255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pack_account ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE pack_account ADD CONSTRAINT FK_467FC6D99D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_467FC6D99D86650F ON pack_account (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pack_account DROP FOREIGN KEY FK_467FC6D99D86650F');
        $this->addSql('DROP INDEX IDX_467FC6D99D86650F ON pack_account');
        $this->addSql('ALTER TABLE pack_account DROP user_id_id');
    }
}
