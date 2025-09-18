<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250918093413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE vehicle_make ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE vehicle_spec ALTER value TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE vehicle_spec_parameter ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE vehicle_spec_parameter ALTER unit TYPE VARCHAR(100)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7BE1DA745E237E06 ON vehicle_spec_parameter (name)');
        $this->addSql('ALTER TABLE vehicle_type ALTER name TYPE VARCHAR(100)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE4364755E237E06 ON vehicle_type (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle_spec ALTER value TYPE VARCHAR(255)');
        $this->addSql('DROP INDEX UNIQ_7BE1DA745E237E06');
        $this->addSql('ALTER TABLE vehicle_spec_parameter ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE vehicle_spec_parameter ALTER unit TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE vehicle_make ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE vehicle ALTER name TYPE VARCHAR(255)');
        $this->addSql('DROP INDEX UNIQ_FE4364755E237E06');
        $this->addSql('ALTER TABLE vehicle_type ALTER name TYPE VARCHAR(255)');
    }
}
