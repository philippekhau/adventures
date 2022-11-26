<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221126155256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adventure (id INT AUTO_INCREMENT NOT NULL, score INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, adventure_id INT NOT NULL, hp INT NOT NULL, armor INT NOT NULL, resting TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_937AB03455CF40F9 (adventure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, adventure_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8F3F68C555CF40F9 (adventure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monster (id INT AUTO_INCREMENT NOT NULL, tile_id INT NOT NULL, armor INT NOT NULL, hp INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_245EC6F4638AF48B (tile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tile (id INT AUTO_INCREMENT NOT NULL, adventure_id INT NOT NULL, active TINYINT(1) DEFAULT 0 NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_768FA90455CF40F9 (adventure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB03455CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C555CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id)');
        $this->addSql('ALTER TABLE monster ADD CONSTRAINT FK_245EC6F4638AF48B FOREIGN KEY (tile_id) REFERENCES tile (id)');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA90455CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB03455CF40F9');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C555CF40F9');
        $this->addSql('ALTER TABLE monster DROP FOREIGN KEY FK_245EC6F4638AF48B');
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA90455CF40F9');
        $this->addSql('DROP TABLE adventure');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE monster');
        $this->addSql('DROP TABLE tile');
    }
}
