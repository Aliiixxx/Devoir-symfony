<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806041550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD stock_xs INT NOT NULL, ADD stock_s INT NOT NULL, ADD stock_m INT NOT NULL, ADD stock_l INT NOT NULL, ADD stock_xl INT NOT NULL, DROP size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD size VARCHAR(2) NOT NULL, DROP stock_xs, DROP stock_s, DROP stock_m, DROP stock_l, DROP stock_xl');
    }
}
