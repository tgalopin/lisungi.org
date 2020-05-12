<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200512214449 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE helpers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admins_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE help_requests_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE volunteers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE helpers (id BIGINT NOT NULL, uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(200) NOT NULL, phone VARCHAR(50) DEFAULT NULL, locality VARCHAR(50) NOT NULL, company VARCHAR(100) DEFAULT NULL, masks INT NOT NULL, glasses INT NOT NULL, blouses INT NOT NULL, gel INT NOT NULL, gloves INT NOT NULL, soap INT NOT NULL, food VARCHAR(250) DEFAULT NULL, other VARCHAR(250) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0E84BFCD17F50A6 ON helpers (uuid)');
        $this->addSql('COMMENT ON COLUMN helpers.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE admins (id BIGINT NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2E0150FF85E0677 ON admins (username)');
        $this->addSql('CREATE TABLE help_requests (id BIGINT NOT NULL, matched_with_id BIGINT DEFAULT NULL, uuid UUID NOT NULL, owner_uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(200) NOT NULL, phone VARCHAR(50) DEFAULT NULL, locality VARCHAR(50) NOT NULL, organization VARCHAR(100) DEFAULT NULL, type VARCHAR(50) NOT NULL, quantity INT NOT NULL, details VARCHAR(250) DEFAULT NULL, finished BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F4BE5251D17F50A6 ON help_requests (uuid)');
        $this->addSql('CREATE INDEX IDX_F4BE525160144EC0 ON help_requests (matched_with_id)');
        $this->addSql('CREATE INDEX help_requests_owner_idx ON help_requests (owner_uuid)');
        $this->addSql('COMMENT ON COLUMN help_requests.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN help_requests.owner_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE volunteers (id BIGINT NOT NULL, uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(200) NOT NULL, phone VARCHAR(50) DEFAULT NULL, locality VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A5E8489D17F50A6 ON volunteers (uuid)');
        $this->addSql('COMMENT ON COLUMN volunteers.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE help_requests ADD CONSTRAINT FK_F4BE525160144EC0 FOREIGN KEY (matched_with_id) REFERENCES helpers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE help_requests DROP CONSTRAINT FK_F4BE525160144EC0');
        $this->addSql('DROP SEQUENCE helpers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admins_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE help_requests_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE volunteers_id_seq CASCADE');
        $this->addSql('DROP TABLE helpers');
        $this->addSql('DROP TABLE admins');
        $this->addSql('DROP TABLE help_requests');
        $this->addSql('DROP TABLE volunteers');
    }
}
