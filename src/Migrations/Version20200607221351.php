<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607221351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE terrain (id INT AUTO_INCREMENT NOT NULL, agence_id INT NOT NULL, nom VARCHAR(255) NOT NULL, largeur DOUBLE PRECISION NOT NULL, longueur DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_C87653B1D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, matricule_fiscale VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) NOT NULL, num_tel VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_64C19AA96C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, date_naissance DATETIME NOT NULL, photo VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, terrain_id INT NOT NULL, note INT NOT NULL, INDEX IDX_CFBDFA1419EB6921 (client_id), INDEX IDX_CFBDFA148A2D8B41 (terrain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, terrain_id INT NOT NULL, client_id INT NOT NULL, date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_42C849558A2D8B41 (terrain_id), INDEX IDX_42C8495519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_entity (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, mot_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), UNIQUE INDEX UNIQ_1D1C63B3B2249712 (mot_passe), UNIQUE INDEX UNIQ_1D1C63B319EB6921 (client_id), UNIQUE INDEX UNIQ_1D1C63B3D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA148A2D8B41 FOREIGN KEY (terrain_id) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558A2D8B41 FOREIGN KEY (terrain_id) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA148A2D8B41');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558A2D8B41');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1D725330D');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D725330D');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1419EB6921');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B319EB6921');
        $this->addSql('DROP TABLE terrain');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE test_entity');
        $this->addSql('DROP TABLE utilisateur');
    }
}
