<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902180533 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_pchelo_child_changes (id INT NOT NULL, childpchelo_id INT NOT NULL, actor_id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, set_pchelomatka_id UUID DEFAULT NULL, set_name VARCHAR(255) DEFAULT NULL, set_content TEXT DEFAULT NULL, set_file_id UUID DEFAULT NULL, set_removed_file_id UUID DEFAULT NULL, set_type VARCHAR(16) DEFAULT NULL, set_status VARCHAR(255) DEFAULT NULL, set_priority SMALLINT DEFAULT NULL, set_kol_child INT DEFAULT NULL, set_goda_vixod INT DEFAULT NULL, set_parent_id INT DEFAULT NULL, set_removed_parent BOOLEAN DEFAULT NULL, set_plan DATE DEFAULT NULL, set_removed_plan BOOLEAN DEFAULT NULL, set_executor_id UUID DEFAULT NULL, set_revoked_executor_id UUID DEFAULT NULL, set_sezon_plem VARCHAR(255) DEFAULT NULL, set_sezon_child VARCHAR(255) DEFAULT NULL, set_urowni INT DEFAULT NULL, PRIMARY KEY(childpchelo_id, id))');
        $this->addSql('CREATE INDEX IDX_3391B980AAEEB5B7 ON admin_pchelo_child_changes (childpchelo_id)');
        $this->addSql('CREATE INDEX IDX_3391B98010DAF24A ON admin_pchelo_child_changes (actor_id)');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.id IS \'(DC2Type:admin_pchelo_child_change_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.actor_id IS \'(DC2Type:admin_uchasties_uchastie_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_pchelomatka_id IS \'(DC2Type:admin_pchelomat_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_file_id IS \'(DC2Type:admin_pchelo_child_file_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_removed_file_id IS \'(DC2Type:admin_pchelo_child_file_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_status IS \'(DC2Type:admin_pchelo_child_status)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_plan IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_executor_id IS \'(DC2Type:admin_uchasties_uchastie_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_changes.set_revoked_executor_id IS \'(DC2Type:admin_uchasties_uchastie_id)\'');
        $this->addSql('ALTER TABLE admin_pchelo_child_changes ADD CONSTRAINT FK_3391B980AAEEB5B7 FOREIGN KEY (childpchelo_id) REFERENCES admin_pchelo_childs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_child_changes ADD CONSTRAINT FK_3391B98010DAF24A FOREIGN KEY (actor_id) REFERENCES admin_uchasties_uchasties (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_child_files ALTER childpchelo_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_child_files ALTER childpchelo_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE admin_pchelo_childs_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE admin_pchelo_child_changes');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_child_files ALTER childpchelo_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_child_files ALTER childpchelo_id DROP DEFAULT');
    }
}
