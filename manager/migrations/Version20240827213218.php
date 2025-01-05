<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827213218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE admin_pchelo_childs_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE admin_pchelo_childs (id INT NOT NULL, pchelomatka_id UUID NOT NULL, author_id UUID NOT NULL, parent_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, zakaz_date DATE DEFAULT NULL, plan_date DATE DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, name VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, type VARCHAR(16) NOT NULL, priority SMALLINT NOT NULL, status VARCHAR(16) NOT NULL, kol_child INT NOT NULL, goda_vixod INT NOT NULL, pchelosezon_plem VARCHAR(255) NOT NULL, pchelosezon_child VARCHAR(255) DEFAULT NULL, urowni INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_670AFFB9CDD581E1 ON admin_pchelo_childs (pchelomatka_id)');
        $this->addSql('CREATE INDEX IDX_670AFFB9F675F31B ON admin_pchelo_childs (author_id)');
        $this->addSql('CREATE INDEX IDX_670AFFB9727ACA70 ON admin_pchelo_childs (parent_id)');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.pchelomatka_id IS \'(DC2Type:admin_pchelomat_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.author_id IS \'(DC2Type:admin_uchasties_uchastie_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.zakaz_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.plan_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.end_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_childs.status IS \'(DC2Type:admin_pchelo_child_status)\'');
        $this->addSql('CREATE TABLE admin_pchelo_child_executors (childpchelo_id INT NOT NULL, uchastie_id UUID NOT NULL, PRIMARY KEY(childpchelo_id, uchastie_id))');
        $this->addSql('CREATE INDEX IDX_BC2B589FAAEEB5B7 ON admin_pchelo_child_executors (childpchelo_id)');
        $this->addSql('CREATE INDEX IDX_BC2B589F6931F8F9 ON admin_pchelo_child_executors (uchastie_id)');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_executors.uchastie_id IS \'(DC2Type:admin_uchasties_uchastie_id)\'');
        $this->addSql('ALTER TABLE admin_pchelo_childs ADD CONSTRAINT FK_670AFFB9CDD581E1 FOREIGN KEY (pchelomatka_id) REFERENCES admin_pchelomats (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_childs ADD CONSTRAINT FK_670AFFB9F675F31B FOREIGN KEY (author_id) REFERENCES admin_uchasties_uchasties (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_childs ADD CONSTRAINT FK_670AFFB9727ACA70 FOREIGN KEY (parent_id) REFERENCES admin_pchelo_childs (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ADD CONSTRAINT FK_BC2B589FAAEEB5B7 FOREIGN KEY (childpchelo_id) REFERENCES admin_pchelo_childs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ADD CONSTRAINT FK_BC2B589F6931F8F9 FOREIGN KEY (uchastie_id) REFERENCES admin_uchasties_uchasties (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_pchelo_childs DROP CONSTRAINT FK_670AFFB9727ACA70');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors DROP CONSTRAINT FK_BC2B589FAAEEB5B7');
        $this->addSql('DROP SEQUENCE admin_pchelo_childs_seq CASCADE');
        $this->addSql('DROP TABLE admin_pchelo_childs');
        $this->addSql('DROP TABLE admin_pchelo_child_executors');
    }
}
