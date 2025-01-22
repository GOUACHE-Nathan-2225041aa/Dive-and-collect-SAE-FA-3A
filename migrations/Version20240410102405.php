<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410102405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bucket_list (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, state VARCHAR(255) DEFAULT NULL, INDEX IDX_DC282CE9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bucket_list_spot (id INT AUTO_INCREMENT NOT NULL, bucket_list_id_id INT NOT NULL, spot_id_id INT NOT NULL, INDEX IDX_D2E71E01C1245B23 (bucket_list_id_id), INDEX IDX_D2E71E015B05007F (spot_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dive (id INT AUTO_INCREMENT NOT NULL, spot_id_id INT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_96BB04405B05007F (spot_id_id), INDEX IDX_96BB04409D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dive_species (id INT AUTO_INCREMENT NOT NULL, species_id_id INT NOT NULL, dive_id_id INT NOT NULL, INDEX IDX_22DEC197C6A6D2CB (species_id_id), INDEX IDX_22DEC1973C49D3BE (dive_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, photo_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_472B783AC51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, photo_id_id INT NOT NULL, user_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AC6340B3C51599E0 (photo_id_id), INDEX IDX_AC6340B39D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oceanarium (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, species_id_id INT NOT NULL, validate TINYINT(1) NOT NULL, INDEX IDX_62955B5D9D86650F (user_id_id), INDEX IDX_62955B5DC6A6D2CB (species_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A3C664D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bucket_list ADD CONSTRAINT FK_DC282CE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bucket_list_spot ADD CONSTRAINT FK_D2E71E01C1245B23 FOREIGN KEY (bucket_list_id_id) REFERENCES bucket_list (id)');
        $this->addSql('ALTER TABLE bucket_list_spot ADD CONSTRAINT FK_D2E71E015B05007F FOREIGN KEY (spot_id_id) REFERENCES spot (id)');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB04405B05007F FOREIGN KEY (spot_id_id) REFERENCES spot (id)');
        $this->addSql('ALTER TABLE dive ADD CONSTRAINT FK_96BB04409D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dive_species ADD CONSTRAINT FK_22DEC197C6A6D2CB FOREIGN KEY (species_id_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE dive_species ADD CONSTRAINT FK_22DEC1973C49D3BE FOREIGN KEY (dive_id_id) REFERENCES dive (id)');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AC51599E0 FOREIGN KEY (photo_id_id) REFERENCES dive (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3C51599E0 FOREIGN KEY (photo_id_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oceanarium ADD CONSTRAINT FK_62955B5D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oceanarium ADD CONSTRAINT FK_62955B5DC6A6D2CB FOREIGN KEY (species_id_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_list DROP FOREIGN KEY FK_DC282CE9D86650F');
        $this->addSql('ALTER TABLE bucket_list_spot DROP FOREIGN KEY FK_D2E71E01C1245B23');
        $this->addSql('ALTER TABLE bucket_list_spot DROP FOREIGN KEY FK_D2E71E015B05007F');
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB04405B05007F');
        $this->addSql('ALTER TABLE dive DROP FOREIGN KEY FK_96BB04409D86650F');
        $this->addSql('ALTER TABLE dive_species DROP FOREIGN KEY FK_22DEC197C6A6D2CB');
        $this->addSql('ALTER TABLE dive_species DROP FOREIGN KEY FK_22DEC1973C49D3BE');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783AC51599E0');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3C51599E0');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B39D86650F');
        $this->addSql('ALTER TABLE oceanarium DROP FOREIGN KEY FK_62955B5D9D86650F');
        $this->addSql('ALTER TABLE oceanarium DROP FOREIGN KEY FK_62955B5DC6A6D2CB');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('DROP TABLE bucket_list');
        $this->addSql('DROP TABLE bucket_list_spot');
        $this->addSql('DROP TABLE dive');
        $this->addSql('DROP TABLE dive_species');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE oceanarium');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('ALTER TABLE user DROP is_verified');
    }
}
