<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240728104141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_pchelomats ADD rasa_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN admin_pchelomats.rasa_id IS \'(DC2Type:dre_ras_id)\'');
        $this->addSql('ALTER TABLE admin_pchelomats ADD CONSTRAINT FK_4C1134681DBD4D8 FOREIGN KEY (rasa_id) REFERENCES dre_rass (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4C1134681DBD4D8 ON admin_pchelomats (rasa_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_pchelomats DROP CONSTRAINT FK_4C1134681DBD4D8');
        $this->addSql('DROP INDEX IDX_4C1134681DBD4D8');
        $this->addSql('ALTER TABLE admin_pchelomats DROP rasa_id');
    }
}
