<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125143825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED111948DC');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED4ACC9A20');
        $this->addSql('ALTER TABLE deck_card ADD id INT AUTO_INCREMENT NOT NULL, ADD qtt INT NOT NULL, DROP qqt, CHANGE zone zone VARCHAR(10) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE post CHANGE deck_id deck_id INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_topic_gestionnairecarte.post');
        $this->addSql('DROP INDEX FK_topic_gestionnairecarte.post ON topic');
        $this->addSql('ALTER TABLE topic DROP post_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post CHANGE deck_id deck_id INT DEFAULT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE deck_card MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED111948DC');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED4ACC9A20');
        $this->addSql('DROP INDEX `PRIMARY` ON deck_card');
        $this->addSql('ALTER TABLE deck_card ADD qqt TINYINT(1) DEFAULT NULL, DROP id, DROP qtt, CHANGE zone zone VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck_card ADD PRIMARY KEY (deck_id, card_id)');
        $this->addSql('ALTER TABLE topic ADD post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_topic_gestionnairecarte.post FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_topic_gestionnairecarte.post ON topic (post_id)');
    }
}
