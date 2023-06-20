<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619214749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podcast ADD id_autor INT DEFAULT NULL');
        $this->addSql('ALTER TABLE podcast ADD CONSTRAINT FK_D7E805BDDF821F8A FOREIGN KEY (id_autor) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_D7E805BDDF821F8A ON podcast (id_autor)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podcast DROP FOREIGN KEY FK_D7E805BDDF821F8A');
        $this->addSql('DROP INDEX IDX_D7E805BDDF821F8A ON podcast');
        $this->addSql('ALTER TABLE podcast DROP id_autor');
    }
}
