<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514151239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, picture VARCHAR(255) DEFAULT NULL, publication_date DATETIME NOT NULL, last_update_date DATETIME NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story_story_category (story_id INT NOT NULL, story_category_id INT NOT NULL, INDEX IDX_533AF7D5AA5D4036 (story_id), INDEX IDX_533AF7D5E26EF55D (story_category_id), PRIMARY KEY(story_id, story_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE story_story_category ADD CONSTRAINT FK_533AF7D5AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_story_category ADD CONSTRAINT FK_533AF7D5E26EF55D FOREIGN KEY (story_category_id) REFERENCES story_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE story_story_category DROP FOREIGN KEY FK_533AF7D5AA5D4036');
        $this->addSql('ALTER TABLE story_story_category DROP FOREIGN KEY FK_533AF7D5E26EF55D');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE story_story_category');
        $this->addSql('DROP TABLE story_category');
    }
}
