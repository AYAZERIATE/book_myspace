<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260914150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds persistent in-app notifications for users.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, recipient_id INT NOT NULL, title VARCHAR(120) NOT NULL, message VARCHAR(1000) NOT NULL, type VARCHAR(30) NOT NULL, link VARCHAR(255) DEFAULT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_BF5476CAE92F8F78 (recipient_id), INDEX notification_recipient_read_created_idx (recipient_id, is_read, created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAE92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE notification');
    }
}
