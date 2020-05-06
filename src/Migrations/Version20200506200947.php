<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200506200947 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE helpers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admins_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE blocked_match_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invitation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE help_requests_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE helpers (id BIGINT NOT NULL, uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(200) NOT NULL, locality VARCHAR(50) NOT NULL, is_company BOOLEAN NOT NULL, masks INT NOT NULL, glasses INT NOT NULL, blouses INT NOT NULL, gel INT NOT NULL, gloves INT NOT NULL, disinfectant INT NOT NULL, paracetamol INT NOT NULL, soap INT NOT NULL, food VARCHAR(250) DEFAULT NULL, other VARCHAR(250) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0E84BFCD17F50A6 ON helpers (uuid)');
        $this->addSql('COMMENT ON COLUMN helpers.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE admins (id BIGINT NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2E0150FF85E0677 ON admins (username)');
        $this->addSql('CREATE TABLE blocked_match (id BIGINT NOT NULL, helper_id BIGINT NOT NULL, owner_uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CD1A84F9D7693E95 ON blocked_match (helper_id)');
        $this->addSql('COMMENT ON COLUMN blocked_match.owner_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE invitation (id INT NOT NULL, email_hash VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F11D61A24E8E423D ON invitation (email_hash)');
        $this->addSql('CREATE TABLE help_requests (id BIGINT NOT NULL, matched_with_id BIGINT DEFAULT NULL, uuid UUID NOT NULL, owner_uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(200) NOT NULL, locality VARCHAR(50) NOT NULL, organization VARCHAR(100) DEFAULT NULL, type VARCHAR(50) NOT NULL, quantity INT NOT NULL, details VARCHAR(250) NOT NULL, finished BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F4BE5251D17F50A6 ON help_requests (uuid)');
        $this->addSql('CREATE INDEX IDX_F4BE525160144EC0 ON help_requests (matched_with_id)');
        $this->addSql('CREATE INDEX help_requests_owner_idx ON help_requests (owner_uuid)');
        $this->addSql('COMMENT ON COLUMN help_requests.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN help_requests.owner_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE blocked_match ADD CONSTRAINT FK_CD1A84F9D7693E95 FOREIGN KEY (helper_id) REFERENCES helpers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE help_requests ADD CONSTRAINT FK_F4BE525160144EC0 FOREIGN KEY (matched_with_id) REFERENCES helpers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blocked_match DROP CONSTRAINT FK_CD1A84F9D7693E95');
        $this->addSql('ALTER TABLE help_requests DROP CONSTRAINT FK_F4BE525160144EC0');
        $this->addSql('DROP SEQUENCE helpers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admins_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE blocked_match_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invitation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE help_requests_id_seq CASCADE');
        $this->addSql('DROP TABLE helpers');
        $this->addSql('DROP TABLE admins');
        $this->addSql('DROP TABLE blocked_match');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE help_requests');
    }
}
