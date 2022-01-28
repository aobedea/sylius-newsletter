<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220128161929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add timestamps to newsletter table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_newsletter ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_newsletter DROP createdAt, DROP updatedAt');
    }
}
