<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603145747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE galerie (id INT AUTO_INCREMENT NOT NULL, upload_by_id INT NOT NULL, note_liaison_id INT NOT NULL, nom VARCHAR(255) NOT NULL, file_size INT NOT NULL, upload_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', file_path VARCHAR(255) NOT NULL, INDEX IDX_9E7D159083BA6D1B (upload_by_id), INDEX IDX_9E7D15906E7438B5 (note_liaison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE galerie ADD CONSTRAINT FK_9E7D159083BA6D1B FOREIGN KEY (upload_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE galerie ADD CONSTRAINT FK_9E7D15906E7438B5 FOREIGN KEY (note_liaison_id) REFERENCES note (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE galerie DROP FOREIGN KEY FK_9E7D159083BA6D1B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE galerie DROP FOREIGN KEY FK_9E7D15906E7438B5
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE galerie
        SQL);
    }
}
