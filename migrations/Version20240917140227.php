<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917140227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_infos ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_infos ADD CONSTRAINT FK_C087935A76ED395 FOREIGN KEY (user_id) REFERENCES user_infos (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C087935A76ED395 ON user_infos (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_infos DROP FOREIGN KEY FK_C087935A76ED395');
        $this->addSql('DROP INDEX UNIQ_C087935A76ED395 ON user_infos');
        $this->addSql('ALTER TABLE user_infos DROP user_id');
    }
}
