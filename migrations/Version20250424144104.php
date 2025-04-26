<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424144104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870F476E05C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6BAF7870F476E05C ON ingredient
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD unit VARCHAR(50) DEFAULT NULL, DROP unit_id_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD unit_id_id INT NOT NULL, DROP unit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870F476E05C FOREIGN KEY (unit_id_id) REFERENCES unit (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6BAF7870F476E05C ON ingredient (unit_id_id)
        SQL);
    }
}
