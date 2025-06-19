<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619084503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE candidatures (id INT AUTO_INCREMENT NOT NULL, ref_offre_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, lettre_motivation VARCHAR(255) NOT NULL, date_candidature DATE NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_DE57CF66CADF96DD (ref_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE magasins (id_magasin INT AUTO_INCREMENT NOT NULL, ville_magasin VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, cp VARCHAR(15) NOT NULL, num_tel VARCHAR(15) NOT NULL, horaire_ouverture TIME NOT NULL, horaire_fermeture TIME NOT NULL, jours_ouverture VARCHAR(255) NOT NULL, video_magasin VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_magasin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE offres_emplois (id INT AUTO_INCREMENT NOT NULL, secteur_activite VARCHAR(255) NOT NULL, titre_poste VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, departement VARCHAR(255) NOT NULL, type_contrat VARCHAR(255) NOT NULL, detail_poste VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, ref_categorie_id INT DEFAULT NULL, ref_sous_categorie_id INT DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_BE2DDF8C580121DE (ref_categorie_id), INDEX IDX_BE2DDF8CD708C9FB (ref_sous_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sous_categories (id INT AUTO_INCREMENT NOT NULL, ref_categorie_id INT DEFAULT NULL, nom_sous_categorie VARCHAR(255) NOT NULL, INDEX IDX_DC8B1382580121DE (ref_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateurs (id_utilisateur INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_497B315EE7927C74 (email), PRIMARY KEY(id_utilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF66CADF96DD FOREIGN KEY (ref_offre_id) REFERENCES offres_emplois (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C580121DE FOREIGN KEY (ref_categorie_id) REFERENCES categories (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CD708C9FB FOREIGN KEY (ref_sous_categorie_id) REFERENCES sous_categories (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sous_categories ADD CONSTRAINT FK_DC8B1382580121DE FOREIGN KEY (ref_categorie_id) REFERENCES categories (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF66CADF96DD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C580121DE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CD708C9FB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sous_categories DROP FOREIGN KEY FK_DC8B1382580121DE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE candidatures
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categories
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE magasins
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE offres_emplois
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produits
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sous_categories
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateurs
        SQL);
    }
}
