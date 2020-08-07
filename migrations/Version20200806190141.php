<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806190141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD account_id INT NOT NULL, CHANGE schedule_time scheduler_datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA79B6B5FBA FOREIGN KEY (account_id) REFERENCES pack_account (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA79B6B5FBA ON event (account_id)');
        $this->addSql('ALTER TABLE pack_account DROP account_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA79B6B5FBA');
        $this->addSql('DROP INDEX IDX_3BAE0AA79B6B5FBA ON event');
        $this->addSql('ALTER TABLE event DROP account_id, CHANGE scheduler_datetime schedule_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE pack_account ADD account_id INT NOT NULL');
    }
}
