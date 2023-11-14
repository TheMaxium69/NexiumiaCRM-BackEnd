<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114145759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention DROP INDEX UNIQ_D11814ABA76ED395, ADD INDEX IDX_D11814ABA76ED395 (user_id)');
        $this->addSql('ALTER TABLE intervention ADD title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention DROP INDEX IDX_D11814ABA76ED395, ADD UNIQUE INDEX UNIQ_D11814ABA76ED395 (user_id)');
        $this->addSql('ALTER TABLE intervention DROP title');
    }
}
