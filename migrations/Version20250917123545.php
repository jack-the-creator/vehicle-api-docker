<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250917123545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle (id SERIAL NOT NULL, make_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, year INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B80E486CFBF73EB ON vehicle (make_id)');
        $this->addSql('CREATE INDEX IDX_1B80E486C54C8C93 ON vehicle (type_id)');
        $this->addSql('CREATE TABLE vehicle_make (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE vehicle_spec (id SERIAL NOT NULL, vehicle_id INT NOT NULL, spec_parameter_id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2932462545317D1 ON vehicle_spec (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_B293246289DB7934 ON vehicle_spec (spec_parameter_id)');
        $this->addSql('CREATE TABLE vehicle_spec_parameter (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, datatype VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE vehicle_type (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486CFBF73EB FOREIGN KEY (make_id) REFERENCES vehicle_make (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C54C8C93 FOREIGN KEY (type_id) REFERENCES vehicle_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle_spec ADD CONSTRAINT FK_B2932462545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle_spec ADD CONSTRAINT FK_B293246289DB7934 FOREIGN KEY (spec_parameter_id) REFERENCES vehicle_spec_parameter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E486CFBF73EB');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E486C54C8C93');
        $this->addSql('ALTER TABLE vehicle_spec DROP CONSTRAINT FK_B2932462545317D1');
        $this->addSql('ALTER TABLE vehicle_spec DROP CONSTRAINT FK_B293246289DB7934');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_make');
        $this->addSql('DROP TABLE vehicle_spec');
        $this->addSql('DROP TABLE vehicle_spec_parameter');
        $this->addSql('DROP TABLE vehicle_type');
    }
}
