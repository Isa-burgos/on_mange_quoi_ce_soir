<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425073927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13F8BD700D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE unit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient DROP quantity, DROP unit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B1379D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DA88B1379D86650F ON recipe
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD image VARCHAR(255) DEFAULT NULL, CHANGE user_id_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_22D1FE13F8BD700D ON recipe_ingredient
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe_ingredient ADD unit VARCHAR(50) DEFAULT NULL, DROP unit_id, CHANGE ingredient_id ingredient_id INT NOT NULL, CHANGE quantity quantity DOUBLE PRECISION NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD quantity NUMERIC(10, 2) DEFAULT NULL, ADD unit VARCHAR(50) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe_ingredient ADD unit_id INT DEFAULT NULL, DROP unit, CHANGE ingredient_id ingredient_id INT DEFAULT NULL, CHANGE quantity quantity NUMERIC(5, 2) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_22D1FE13F8BD700D ON recipe_ingredient (unit_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DA88B137A76ED395 ON recipe
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP image, CHANGE user_id user_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD CONSTRAINT FK_DA88B1379D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA88B1379D86650F ON recipe (user_id_id)
        SQL);
    }
}
