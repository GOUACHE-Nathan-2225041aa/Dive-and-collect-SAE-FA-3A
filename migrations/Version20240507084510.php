<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507084510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_list DROP FOREIGN KEY FK_DC282CE9D86650F');
        $this->addSql('DROP INDEX IDX_DC282CE9D86650F ON bucket_list');
        $this->addSql('ALTER TABLE bucket_list CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE bucket_list ADD CONSTRAINT FK_DC282CEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DC282CEA76ED395 ON bucket_list (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_list DROP FOREIGN KEY FK_DC282CEA76ED395');
        $this->addSql('DROP INDEX IDX_DC282CEA76ED395 ON bucket_list');
        $this->addSql('ALTER TABLE bucket_list CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE bucket_list ADD CONSTRAINT FK_DC282CE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DC282CE9D86650F ON bucket_list (user_id_id)');
    }
}
