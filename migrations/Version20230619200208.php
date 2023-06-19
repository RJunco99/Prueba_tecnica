<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619200208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BD14D45BBE');
        $this->addSql('DROP INDEX IDX_D7E805BD14D45BBE ON podcast');
        $this->addSql('ALTER TABLE podcast ADD autor VARCHAR(255) NOT NULL, DROP autor_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podcast ADD autor_id INT NOT NULL, DROP autor');
        $this->addSql('ALTER TABLE podcast ADD CONSTRAINT FK_D7E805BD14D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_D7E805BD14D45BBE ON podcast (autor_id)');
    }
}
