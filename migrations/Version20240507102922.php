<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507102922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB0440A76ED395');
        $this->addSql('DROP INDEX IDX_96BB0440A76ED395 ON dive');
        $this->addSql('ALTER TABLE dive DROP user_id');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783A7E9E4C8C');
        $this->addSql('DROP INDEX IDX_472B783A7E9E4C8C ON gallery');
        $this->addSql('ALTER TABLE gallery CHANGE photo_id dive_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A2E04E766 FOREIGN KEY (dive_id) REFERENCES dive (id)');
        $this->addSql('CREATE INDEX IDX_472B783A2E04E766 ON gallery (dive_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783A2E04E766');
        $this->addSql('DROP INDEX IDX_472B783A2E04E766 ON gallery');
        $this->addSql('ALTER TABLE gallery CHANGE dive_id photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A7E9E4C8C FOREIGN KEY (photo_id) REFERENCES dive (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_472B783A7E9E4C8C ON gallery (photo_id)');
        $this->addSql('ALTER TABLE dive ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB0440A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_96BB0440A76ED395 ON dive (user_id)');
    }
}
