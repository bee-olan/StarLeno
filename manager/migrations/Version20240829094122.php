<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240829094122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER parent_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE admin_pchelo_childs ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id TYPE INT');
        $this->addSql('ALTER TABLE admin_pchelo_child_executors ALTER childpchelo_id DROP DEFAULT');
        $this->addSql('ALTER TABLE admin_pchelomats ADD korotko_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE admin_pchelo_childs_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE admin_pchelomats DROP korotko_name');
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
