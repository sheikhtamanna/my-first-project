<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227082905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id SERIAL PRIMARY KEY, user_id INT NOT NULL, date_of_commande TIMESTAMP NOT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE commande_detail (id SERIAL PRIMARY KEY, commande_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id)');
        $this->addSql('ALTER TABLE commande_detail ADD CONSTRAINT FK_2C52844682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_detail ADD CONSTRAINT FK_2C5284464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE commande_detail DROP FOREIGN KEY FK_2C52844682EA2E54');
        $this->addSql('ALTER TABLE commande_detail DROP FOREIGN KEY FK_2C5284464584665A');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_detail');
    }
}
