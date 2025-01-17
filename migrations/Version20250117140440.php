<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117140440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE TABLE habitat_images (habitat_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_4A4A18D1AFFE2D26 (habitat_id), INDEX IDX_4A4A18D13DA5256D (image_id), PRIMARY KEY(habitat_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        //$this->addSql('ALTER TABLE habitat_images ADD CONSTRAINT FK_4A4A18D1AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE habitat_images ADD CONSTRAINT FK_4A4A18D13DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal ADD habitat_id INT NOT NULL, ADD race VARCHAR(255) NOT NULL, ADD image VARCHAR(255) DEFAULT NULL, DROP etat, CHANGE prenom prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FAFFE2D26 ON animal (habitat_id)');
        $this->addSql('ALTER TABLE habitat CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE commentaire_habitat commentaire_habitat LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE description description VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habitat_images DROP FOREIGN KEY FK_4A4A18D1AFFE2D26');
        $this->addSql('ALTER TABLE habitat_images DROP FOREIGN KEY FK_4A4A18D13DA5256D');
        $this->addSql('DROP TABLE habitat_images');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FAFFE2D26');
        $this->addSql('DROP INDEX IDX_6AAB231FAFFE2D26 ON animal');
        $this->addSql('ALTER TABLE animal ADD etat VARCHAR(50) NOT NULL, DROP habitat_id, DROP race, DROP image, CHANGE prenom prenom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE habitat CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE description description VARCHAR(50) NOT NULL, CHANGE commentaire_habitat commentaire_habitat VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE service CHANGE description description TEXT DEFAULT NULL');
    }
}
