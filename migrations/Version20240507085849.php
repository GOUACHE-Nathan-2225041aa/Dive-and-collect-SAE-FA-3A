<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507085849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_list_spot DROP FOREIGN KEY FK_D2E71E015B05007F');
        $this->addSql('ALTER TABLE bucket_list_spot DROP FOREIGN KEY FK_D2E71E01C1245B23');
        $this->addSql('DROP INDEX IDX_D2E71E01C1245B23 ON bucket_list_spot');
        $this->addSql('DROP INDEX IDX_D2E71E015B05007F ON bucket_list_spot');
        $this->addSql('ALTER TABLE bucket_list_spot ADD bucket_list_id INT NOT NULL, ADD spot_id INT NOT NULL, DROP bucket_list_id_id, DROP spot_id_id');
        $this->addSql('ALTER TABLE bucket_list_spot ADD CONSTRAINT FK_D2E71E0181AF2722 FOREIGN KEY (bucket_list_id) REFERENCES bucket_list (id)');
        $this->addSql('ALTER TABLE bucket_list_spot ADD CONSTRAINT FK_D2E71E012DF1D37C FOREIGN KEY (spot_id) REFERENCES spot (id)');
        $this->addSql('CREATE INDEX IDX_D2E71E0181AF2722 ON bucket_list_spot (bucket_list_id)');
        $this->addSql('CREATE INDEX IDX_D2E71E012DF1D37C ON bucket_list_spot (spot_id)');
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB04405B05007F');
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB04409D86650F');
        $this->addSql('DROP INDEX IDX_96BB04409D86650F ON dive');
        $this->addSql('DROP INDEX IDX_96BB04405B05007F ON dive');
        $this->addSql('ALTER TABLE dive ADD spot_id INT NOT NULL, ADD user_id INT NOT NULL, DROP spot_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB04402DF1D37C FOREIGN KEY (spot_id) REFERENCES spot (id)');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB0440A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_96BB04402DF1D37C ON dive (spot_id)');
        $this->addSql('CREATE INDEX IDX_96BB0440A76ED395 ON dive (user_id)');
        $this->addSql('ALTER TABLE dive_species DROP FOREIGN KEY FK_22DEC1973C49D3BE');
        $this->addSql('ALTER TABLE dive_species DROP FOREIGN KEY FK_22DEC197C6A6D2CB');
        $this->addSql('DROP INDEX IDX_22DEC197C6A6D2CB ON dive_species');
        $this->addSql('DROP INDEX IDX_22DEC1973C49D3BE ON dive_species');
        $this->addSql('ALTER TABLE dive_species ADD species_id INT NOT NULL, ADD dive_id INT NOT NULL, DROP species_id_id, DROP dive_id_id');
        $this->addSql('ALTER TABLE dive_species ADD CONSTRAINT FK_22DEC197B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE dive_species ADD CONSTRAINT FK_22DEC1972E04E766 FOREIGN KEY (dive_id) REFERENCES dive (id)');
        $this->addSql('CREATE INDEX IDX_22DEC197B2A1D860 ON dive_species (species_id)');
        $this->addSql('CREATE INDEX IDX_22DEC1972E04E766 ON dive_species (dive_id)');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783AC51599E0');
        $this->addSql('DROP INDEX IDX_472B783AC51599E0 ON gallery');
        $this->addSql('ALTER TABLE gallery CHANGE photo_id_id photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A7E9E4C8C FOREIGN KEY (photo_id) REFERENCES dive (id)');
        $this->addSql('CREATE INDEX IDX_472B783A7E9E4C8C ON gallery (photo_id)');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B39D86650F');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3C51599E0');
        $this->addSql('DROP INDEX IDX_AC6340B3C51599E0 ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B39D86650F ON `like`');
        $this->addSql('ALTER TABLE `like` ADD photo_id INT NOT NULL, ADD user_id INT NOT NULL, DROP photo_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B37E9E4C8C FOREIGN KEY (photo_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B37E9E4C8C ON `like` (photo_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3A76ED395 ON `like` (user_id)');
        $this->addSql('ALTER TABLE oceanarium DROP FOREIGN KEY FK_62955B5D9D86650F');
        $this->addSql('ALTER TABLE oceanarium DROP FOREIGN KEY FK_62955B5DC6A6D2CB');
        $this->addSql('DROP INDEX IDX_62955B5DC6A6D2CB ON oceanarium');
        $this->addSql('DROP INDEX IDX_62955B5D9D86650F ON oceanarium');
        $this->addSql('ALTER TABLE oceanarium ADD user_id INT NOT NULL, ADD species_id INT NOT NULL, DROP user_id_id, DROP species_id_id');
        $this->addSql('ALTER TABLE oceanarium ADD CONSTRAINT FK_62955B5DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oceanarium ADD CONSTRAINT FK_62955B5DB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('CREATE INDEX IDX_62955B5DA76ED395 ON oceanarium (user_id)');
        $this->addSql('CREATE INDEX IDX_62955B5DB2A1D860 ON oceanarium (species_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783A7E9E4C8C');
        $this->addSql('DROP INDEX IDX_472B783A7E9E4C8C ON gallery');
        $this->addSql('ALTER TABLE gallery CHANGE photo_id photo_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AC51599E0 FOREIGN KEY (photo_id_id) REFERENCES dive (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_472B783AC51599E0 ON gallery (photo_id_id)');
        $this->addSql('ALTER TABLE dive_species DROP FOREIGN KEY FK_22DEC197B2A1D860');
        $this->addSql('ALTER TABLE dive_species DROP FOREIGN KEY FK_22DEC1972E04E766');
        $this->addSql('DROP INDEX IDX_22DEC197B2A1D860 ON dive_species');
        $this->addSql('DROP INDEX IDX_22DEC1972E04E766 ON dive_species');
        $this->addSql('ALTER TABLE dive_species ADD species_id_id INT NOT NULL, ADD dive_id_id INT NOT NULL, DROP species_id, DROP dive_id');
        $this->addSql('ALTER TABLE dive_species ADD CONSTRAINT FK_22DEC1973C49D3BE FOREIGN KEY (dive_id_id) REFERENCES dive (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE dive_species ADD CONSTRAINT FK_22DEC197C6A6D2CB FOREIGN KEY (species_id_id) REFERENCES species (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_22DEC197C6A6D2CB ON dive_species (species_id_id)');
        $this->addSql('CREATE INDEX IDX_22DEC1973C49D3BE ON dive_species (dive_id_id)');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B37E9E4C8C');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('DROP INDEX IDX_AC6340B37E9E4C8C ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B3A76ED395 ON `like`');
        $this->addSql('ALTER TABLE `like` ADD photo_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP photo_id, DROP user_id');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3C51599E0 FOREIGN KEY (photo_id_id) REFERENCES gallery (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AC6340B3C51599E0 ON `like` (photo_id_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B39D86650F ON `like` (user_id_id)');
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB04402DF1D37C');
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB0440A76ED395');
        $this->addSql('DROP INDEX IDX_96BB04402DF1D37C ON dive');
        $this->addSql('DROP INDEX IDX_96BB0440A76ED395 ON dive');
        $this->addSql('ALTER TABLE dive ADD spot_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP spot_id, DROP user_id');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB04405B05007F FOREIGN KEY (spot_id_id) REFERENCES spot (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB04409D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_96BB04409D86650F ON dive (user_id_id)');
        $this->addSql('CREATE INDEX IDX_96BB04405B05007F ON dive (spot_id_id)');
        $this->addSql('ALTER TABLE bucket_list_spot DROP FOREIGN KEY FK_D2E71E0181AF2722');
        $this->addSql('ALTER TABLE bucket_list_spot DROP FOREIGN KEY FK_D2E71E012DF1D37C');
        $this->addSql('DROP INDEX IDX_D2E71E0181AF2722 ON bucket_list_spot');
        $this->addSql('DROP INDEX IDX_D2E71E012DF1D37C ON bucket_list_spot');
        $this->addSql('ALTER TABLE bucket_list_spot ADD bucket_list_id_id INT NOT NULL, ADD spot_id_id INT NOT NULL, DROP bucket_list_id, DROP spot_id');
        $this->addSql('ALTER TABLE bucket_list_spot ADD CONSTRAINT FK_D2E71E015B05007F FOREIGN KEY (spot_id_id) REFERENCES spot (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bucket_list_spot ADD CONSTRAINT FK_D2E71E01C1245B23 FOREIGN KEY (bucket_list_id_id) REFERENCES bucket_list (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D2E71E01C1245B23 ON bucket_list_spot (bucket_list_id_id)');
        $this->addSql('CREATE INDEX IDX_D2E71E015B05007F ON bucket_list_spot (spot_id_id)');
        $this->addSql('ALTER TABLE oceanarium DROP FOREIGN KEY FK_62955B5DA76ED395');
        $this->addSql('ALTER TABLE oceanarium DROP FOREIGN KEY FK_62955B5DB2A1D860');
        $this->addSql('DROP INDEX IDX_62955B5DA76ED395 ON oceanarium');
        $this->addSql('DROP INDEX IDX_62955B5DB2A1D860 ON oceanarium');
        $this->addSql('ALTER TABLE oceanarium ADD user_id_id INT NOT NULL, ADD species_id_id INT NOT NULL, DROP user_id, DROP species_id');
        $this->addSql('ALTER TABLE oceanarium ADD CONSTRAINT FK_62955B5D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE oceanarium ADD CONSTRAINT FK_62955B5DC6A6D2CB FOREIGN KEY (species_id_id) REFERENCES species (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_62955B5DC6A6D2CB ON oceanarium (species_id_id)');
        $this->addSql('CREATE INDEX IDX_62955B5D9D86650F ON oceanarium (user_id_id)');
    }
}
