<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211029192756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE odiseo_avatax_configuration (id INT AUTO_INCREMENT NOT NULL, sender_data_id INT DEFAULT NULL, zone_id INT NOT NULL, app_name VARCHAR(255) NOT NULL, app_version VARCHAR(255) NOT NULL, machine_name VARCHAR(255) DEFAULT NULL, sandbox TINYINT(1) NOT NULL, account_id INT NOT NULL, license_key VARCHAR(255) NOT NULL, shipping_tax_code VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CCFA3DE25B0D5BA6 (app_name), UNIQUE INDEX UNIQ_CCFA3DE2BA0367F7 (sender_data_id), INDEX IDX_CCFA3DE29F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_avatax_configuration_sender_data (id INT AUTO_INCREMENT NOT NULL, province_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odiseo_avatax_configuration ADD CONSTRAINT FK_CCFA3DE2BA0367F7 FOREIGN KEY (sender_data_id) REFERENCES odiseo_avatax_configuration_sender_data (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_avatax_configuration ADD CONSTRAINT FK_CCFA3DE29F2C3FAB FOREIGN KEY (zone_id) REFERENCES sylius_zone (id)');
        $this->addSql('ALTER TABLE sylius_product ADD avatax_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE odiseo_avatax_configuration DROP FOREIGN KEY FK_CCFA3DE2BA0367F7');
        $this->addSql('DROP TABLE odiseo_avatax_configuration');
        $this->addSql('DROP TABLE odiseo_avatax_configuration_sender_data');
        $this->addSql('ALTER TABLE sylius_product DROP avatax_code');
    }
}
