<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613213546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F64CE905FD');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F63696404A');
        $this->addSql('DROP INDEX IDX_72AAD4F64CE905FD ON shopping_cart');
        $this->addSql('DROP INDEX IDX_72AAD4F63696404A ON shopping_cart');
        $this->addSql('ALTER TABLE shopping_cart ADD product_id INT DEFAULT NULL, DROP countable_product_id, DROP weighted_product_id');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F64584665A ON shopping_cart (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F64584665A');
        $this->addSql('DROP INDEX IDX_72AAD4F64584665A ON shopping_cart');
        $this->addSql('ALTER TABLE shopping_cart ADD weighted_product_id INT DEFAULT NULL, CHANGE product_id countable_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F64CE905FD FOREIGN KEY (countable_product_id) REFERENCES countable_product (id)');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F63696404A FOREIGN KEY (weighted_product_id) REFERENCES weighted_product (id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F64CE905FD ON shopping_cart (countable_product_id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F63696404A ON shopping_cart (weighted_product_id)');
    }
}
