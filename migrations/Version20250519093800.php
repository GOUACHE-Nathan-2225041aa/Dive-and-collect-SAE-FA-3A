<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519093800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE entreprises_offres_entreprises (entreprises_id INT NOT NULL, offres_entreprises_id INT NOT NULL, INDEX IDX_7B4D882DA70A18EC (entreprises_id), INDEX IDX_7B4D882D22B6DB4D (offres_entreprises_id), PRIMARY KEY(entreprises_id, offres_entreprises_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprises_offres_entreprises ADD CONSTRAINT FK_7B4D882DA70A18EC FOREIGN KEY (entreprises_id) REFERENCES entreprises (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprises_offres_entreprises ADD CONSTRAINT FK_7B4D882D22B6DB4D FOREIGN KEY (offres_entreprises_id) REFERENCES offres_entreprises (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprises_offres_entreprises DROP FOREIGN KEY FK_7B4D882DA70A18EC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprises_offres_entreprises DROP FOREIGN KEY FK_7B4D882D22B6DB4D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE entreprises_offres_entreprises
        SQL);
    }
}
