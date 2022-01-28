<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220128155537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create relation between newsletter and customer tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_customer_newsletter (newsletter_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_834C814A22DB1917 (newsletter_id), INDEX IDX_834C814A9395C3F3 (customer_id), PRIMARY KEY(newsletter_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_customer_newsletter ADD CONSTRAINT FK_834C814A22DB1917 FOREIGN KEY (newsletter_id) REFERENCES sylius_newsletter (id)');
        $this->addSql('ALTER TABLE sylius_customer_newsletter ADD CONSTRAINT FK_834C814A9395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_customer_newsletter');
    }
}
