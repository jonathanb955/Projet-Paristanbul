<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250619132414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE log_activite (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, action VARCHAR(255) NOT NULL, detail VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_D6269ECAFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE log_activite ADD CONSTRAINT FK_D6269ECAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id_utilisateur)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE log_activite DROP FOREIGN KEY FK_D6269ECAFB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE log_activite
        SQL);
    }
}
