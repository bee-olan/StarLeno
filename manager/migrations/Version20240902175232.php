<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902175232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_pchelo_child_files (id UUID NOT NULL, childpchelo_id INT NOT NULL, uchastie_id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, info_path VARCHAR(255) NOT NULL, info_name VARCHAR(255) NOT NULL, info_size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_92A29136AAEEB5B7 ON admin_pchelo_child_files (childpchelo_id)');
        $this->addSql('CREATE INDEX IDX_92A291366931F8F9 ON admin_pchelo_child_files (uchastie_id)');
        $this->addSql('CREATE INDEX IDX_92A29136AA9E377A ON admin_pchelo_child_files (date)');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_files.id IS \'(DC2Type:admin_pchelo_child_file_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_files.uchastie_id IS \'(DC2Type:admin_uchasties_uchastie_id)\'');
        $this->addSql('COMMENT ON COLUMN admin_pchelo_child_files.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE admin_pchelo_child_files ADD CONSTRAINT FK_92A29136AAEEB5B7 FOREIGN KEY (childpchelo_id) REFERENCES admin_pchelo_childs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_pchelo_child_files ADD CONSTRAINT FK_92A291366931F8F9 FOREIGN KEY (uchastie_id) REFERENCES admin_uchasties_uchasties (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
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
        $this->addSql('DROP TABLE admin_pchelo_child_files');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id DROP DEFAULT');
    }
}
