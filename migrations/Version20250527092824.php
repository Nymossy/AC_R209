<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527092824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE vie_note ADD note_id INT NOT NULL, ADD modified_by_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vie_note ADD CONSTRAINT FK_1DB0ADB326ED0855 FOREIGN KEY (note_id) REFERENCES note (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vie_note ADD CONSTRAINT FK_1DB0ADB399049ECE FOREIGN KEY (modified_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1DB0ADB326ED0855 ON vie_note (note_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1DB0ADB399049ECE ON vie_note (modified_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE vie_note DROP FOREIGN KEY FK_1DB0ADB326ED0855
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vie_note DROP FOREIGN KEY FK_1DB0ADB399049ECE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1DB0ADB326ED0855 ON vie_note
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1DB0ADB399049ECE ON vie_note
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vie_note DROP note_id, DROP modified_by_id
        SQL);
    }
}
