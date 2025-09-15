<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250915081316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DB29FAD6');
        $this->addSql('DROP INDEX IDX_E418C74DB29FAD6 ON exercice');
        $this->addSql('ALTER TABLE exercice CHANGE workoutplan_id workout_plan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D945F6E33 FOREIGN KEY (workout_plan_id) REFERENCES workout_plan (id)');
        $this->addSql('CREATE INDEX IDX_E418C74D945F6E33 ON exercice (workout_plan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D945F6E33');
        $this->addSql('DROP INDEX IDX_E418C74D945F6E33 ON exercice');
        $this->addSql('ALTER TABLE exercice CHANGE workout_plan_id workoutplan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DB29FAD6 FOREIGN KEY (workoutplan_id) REFERENCES workout_plan (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E418C74DB29FAD6 ON exercice (workoutplan_id)');
    }
}
