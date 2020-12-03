<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203132243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE community (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE community_user (community_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4CC23C83FDA7B0BF (community_id), INDEX IDX_4CC23C83A76ED395 (user_id), PRIMARY KEY(community_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE community_user ADD CONSTRAINT FK_4CC23C83FDA7B0BF FOREIGN KEY (community_id) REFERENCES community (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE community_user ADD CONSTRAINT FK_4CC23C83A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD community_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7FDA7B0BF FOREIGN KEY (community_id) REFERENCES community (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7FDA7B0BF ON event (community_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE community_user DROP FOREIGN KEY FK_4CC23C83FDA7B0BF');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7FDA7B0BF');
        $this->addSql('DROP TABLE community');
        $this->addSql('DROP TABLE community_user');
        $this->addSql('DROP INDEX IDX_3BAE0AA7FDA7B0BF ON event');
        $this->addSql('ALTER TABLE event DROP community_id');
    }
}
