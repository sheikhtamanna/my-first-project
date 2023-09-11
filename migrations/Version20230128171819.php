<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128171819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catagory (id  SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE product (id  SERIAL PRIMARY KEY, catagory_id INT NOT NULL, title VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, description TEXT NOT NULL)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD960C9318 FOREIGN KEY (catagory_id) REFERENCES catagory (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD960C9318');
        $this->addSql('DROP TABLE catagory');
        $this->addSql('DROP TABLE product');
    }
}
