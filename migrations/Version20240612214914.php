<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612214914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, inventory_id INT NOT NULL, countable_product_id INT DEFAULT NULL, weighted_product_id INT DEFAULT NULL, is_countable TINYINT(1) NOT NULL, INDEX IDX_F0FE25279EEA759 (inventory_id), INDEX IDX_F0FE25274CE905FD (countable_product_id), INDEX IDX_F0FE25273696404A (weighted_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, last_name VARCHAR(25) NOT NULL, email VARCHAR(30) NOT NULL, phone VARCHAR(15) NOT NULL, type_identity VARCHAR(2) NOT NULL, number_identity VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countable_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, total_amount INT NOT NULL, INDEX IDX_F2ABAA244584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_FB45F43919EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_cart (id INT AUTO_INCREMENT NOT NULL, shopping_id INT NOT NULL, product_id INT DEFAULT NULL, sell_price DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_72AAD4F63B9D8A72 (shopping_id), INDEX IDX_72AAD4F64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weighted_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, INDEX IDX_7A52499C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25279EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274CE905FD FOREIGN KEY (countable_product_id) REFERENCES countable_product (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25273696404A FOREIGN KEY (weighted_product_id) REFERENCES weighted_product (id)');
        $this->addSql('ALTER TABLE countable_product ADD CONSTRAINT FK_F2ABAA244584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE shopping ADD CONSTRAINT FK_FB45F43919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F63B9D8A72 FOREIGN KEY (shopping_id) REFERENCES shopping (id)');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE weighted_product ADD CONSTRAINT FK_7A52499C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25279EEA759');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274CE905FD');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25273696404A');
        $this->addSql('ALTER TABLE countable_product DROP FOREIGN KEY FK_F2ABAA244584665A');
        $this->addSql('ALTER TABLE shopping DROP FOREIGN KEY FK_FB45F43919EB6921');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F63B9D8A72');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F64584665A');
        $this->addSql('ALTER TABLE weighted_product DROP FOREIGN KEY FK_7A52499C4584665A');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE countable_product');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE shopping');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE weighted_product');
    }
}
