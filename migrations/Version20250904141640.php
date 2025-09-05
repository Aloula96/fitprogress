<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904141640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE body_weight (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, weight_value DOUBLE PRECISION NOT NULL, recoded_at DATETIME NOT NULL, INDEX IDX_92D1F647A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE body_weight ADD CONSTRAINT FK_92D1F647A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercice ADD workoutplan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DB29FAD6 FOREIGN KEY (workoutplan_id) REFERENCES workout_plan (id)');
        $this->addSql('CREATE INDEX IDX_E418C74DB29FAD6 ON exercice (workoutplan_id)');
        $this->addSql('ALTER TABLE goal ADD workoutplan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EB29FAD6 FOREIGN KEY (workoutplan_id) REFERENCES workout_plan (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCDCEB2EB29FAD6 ON goal (workoutplan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE body_weight DROP FOREIGN KEY FK_92D1F647A76ED395');
        $this->addSql('DROP TABLE body_weight');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DB29FAD6');
        $this->addSql('DROP INDEX IDX_E418C74DB29FAD6 ON exercice');
        $this->addSql('ALTER TABLE exercice DROP workoutplan_id');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2EB29FAD6');
        $this->addSql('DROP INDEX UNIQ_FCDCEB2EB29FAD6 ON goal');
        $this->addSql('ALTER TABLE goal DROP workoutplan_id');
    }
}
