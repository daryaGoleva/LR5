<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200614111506 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, INDEX IDX_F529939819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_dish (order_id INT NOT NULL, dish_id INT NOT NULL, INDEX IDX_D88CB6AF8D9F6D38 (order_id), INDEX IDX_D88CB6AF148EB0CB (dish_id), PRIMARY KEY(order_id, dish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_957D8CB8B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(15) NOT NULL, address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE order_dish ADD CONSTRAINT FK_D88CB6AF8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_dish ADD CONSTRAINT FK_D88CB6AF148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_dish DROP FOREIGN KEY FK_D88CB6AF8D9F6D38');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8B1E7706E');
        $this->addSql('ALTER TABLE order_dish DROP FOREIGN KEY FK_D88CB6AF148EB0CB');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_dish');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE client');
    }
}
