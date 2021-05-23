<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521002344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agents (name VARCHAR(32) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent_properties (agent VARCHAR(32) NOT NULL, property_id INT NOT NULL, INDEX IDX_F685E0C8268B9C9D (agent), INDEX IDX_F685E0C8549213EC (property_id), PRIMARY KEY(agent, property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE properties (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(32) DEFAULT NULL, property_identifier CHAR(36) NOT NULL, country VARCHAR(255) DEFAULT NULL, town VARCHAR(255) DEFAULT NULL, description VARCHAR(2000) DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(11, 8) NOT NULL, num_bedrooms INT DEFAULT 0 NOT NULL, num_bathrooms INT DEFAULT 0 NOT NULL, price NUMERIC(15, 2) DEFAULT \'0\' NOT NULL, property_type JSON DEFAULT NULL, INDEX IDX_87C331C78CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_types (name VARCHAR(32) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent_properties ADD CONSTRAINT FK_F685E0C8268B9C9D FOREIGN KEY (agent) REFERENCES agents (name) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent_properties ADD CONSTRAINT FK_F685E0C8549213EC FOREIGN KEY (property_id) REFERENCES properties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE properties ADD CONSTRAINT FK_87C331C78CDE5729 FOREIGN KEY (type) REFERENCES property_types (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent_properties DROP FOREIGN KEY FK_F685E0C8268B9C9D');
        $this->addSql('ALTER TABLE agent_properties DROP FOREIGN KEY FK_F685E0C8549213EC');
        $this->addSql('ALTER TABLE properties DROP FOREIGN KEY FK_87C331C78CDE5729');
        $this->addSql('DROP TABLE agents');
        $this->addSql('DROP TABLE agent_properties');
        $this->addSql('DROP TABLE properties');
        $this->addSql('DROP TABLE property_types');
    }
}
