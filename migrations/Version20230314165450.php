<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314165450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` ADD famille_id INT DEFAULT NULL, ADD family VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB03497A77B84 FOREIGN KEY (famille_id) REFERENCES family (id)');
        $this->addSql('CREATE INDEX IDX_937AB03497A77B84 ON `character` (famille_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB03497A77B84');
        $this->addSql('DROP INDEX IDX_937AB03497A77B84 ON `character`');
        $this->addSql('ALTER TABLE `character` DROP famille_id, DROP family');
    }
}
