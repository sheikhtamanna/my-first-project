<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226225822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "order" (id  SERIAL PRIMARY KEY, user_id INT NOT NULL, date_of_order TIMESTAMP NOT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE order_details (id SERIAL PRIMARY KEY, commande_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C182EA2E54 FOREIGN KEY (commande_id) REFERENCES "order" (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C182EA2E54');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C14584665A');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_details');
    }
}
