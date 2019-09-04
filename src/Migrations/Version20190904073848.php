<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190904073848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE end (id INT AUTO_INCREMENT NOT NULL, date_time DATETIME NOT NULL, time_zone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE start (id INT AUTO_INCREMENT NOT NULL, date_time DATETIME NOT NULL, time_zone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attendees (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_C8C96B2571F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recurrence (id INT AUTO_INCREMENT NOT NULL, frequence VARCHAR(255) DEFAULT NULL, count INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE greeting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reminder (id INT AUTO_INCREMENT NOT NULL, userdefault TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, start_id INT NOT NULL, end_id INT NOT NULL, recurence_id INT DEFAULT NULL, reminder_id INT DEFAULT NULL, user_id INT NOT NULL, google_id VARCHAR(255) DEFAULT NULL, summary VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3BAE0AA7623DF99B (start_id), UNIQUE INDEX UNIQ_3BAE0AA7E2BD8A10 (end_id), UNIQUE INDEX UNIQ_3BAE0AA75CAC579C (recurence_id), UNIQUE INDEX UNIQ_3BAE0AA7D987BE75 (reminder_id), INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE overrides (id INT AUTO_INCREMENT NOT NULL, reminder_id INT NOT NULL, method VARCHAR(255) NOT NULL, unit VARCHAR(255) NOT NULL, number INT NOT NULL, INDEX IDX_CBECDAFAD987BE75 (reminder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendees ADD CONSTRAINT FK_C8C96B2571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7623DF99B FOREIGN KEY (start_id) REFERENCES start (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E2BD8A10 FOREIGN KEY (end_id) REFERENCES end (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA75CAC579C FOREIGN KEY (recurence_id) REFERENCES recurrence (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D987BE75 FOREIGN KEY (reminder_id) REFERENCES reminder (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE overrides ADD CONSTRAINT FK_CBECDAFAD987BE75 FOREIGN KEY (reminder_id) REFERENCES reminder (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7E2BD8A10');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7623DF99B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA75CAC579C');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D987BE75');
        $this->addSql('ALTER TABLE overrides DROP FOREIGN KEY FK_CBECDAFAD987BE75');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE attendees DROP FOREIGN KEY FK_C8C96B2571F7E88B');
        $this->addSql('DROP TABLE end');
        $this->addSql('DROP TABLE start');
        $this->addSql('DROP TABLE attendees');
        $this->addSql('DROP TABLE recurrence');
        $this->addSql('DROP TABLE greeting');
        $this->addSql('DROP TABLE reminder');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE overrides');
    }
}
