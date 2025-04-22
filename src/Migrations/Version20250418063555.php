<?php

/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration;

final class Version20250418063555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_cms_block (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(64) NOT NULL, name VARCHAR(250) DEFAULT NULL, template VARCHAR(250) DEFAULT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9D2248BC77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_block_channels (block_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_7026602FE9ED820C (block_id), INDEX IDX_7026602F72F5A1AA (channel_id), PRIMARY KEY(block_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_block_products (block_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_3088D8C3E9ED820C (block_id), INDEX IDX_3088D8C34584665A (product_id), PRIMARY KEY(block_id, product_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_block_taxons (block_id INT NOT NULL, taxon_id INT NOT NULL, INDEX IDX_5397DD03E9ED820C (block_id), INDEX IDX_5397DD03DE13F470 (taxon_id), PRIMARY KEY(block_id, taxon_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_block_products_in_taxons (block_id INT NOT NULL, taxon_id INT NOT NULL, INDEX IDX_B4D0B7CEE9ED820C (block_id), INDEX IDX_B4D0B7CEDE13F470 (taxon_id), PRIMARY KEY(block_id, taxon_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_content_configuration (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, page_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration JSON NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_BB97608DE9ED820C (block_id), INDEX IDX_BB97608DC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_media (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(250) NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(250) NOT NULL, path VARCHAR(250) NOT NULL, mime_type VARCHAR(250) DEFAULT NULL, enabled TINYINT(1) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, UNIQUE INDEX UNIQ_74157E9277153098 (code), UNIQUE INDEX UNIQ_74157E92B548B0F (path), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_media_channels (media_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_2538B272EA9FDD75 (media_id), INDEX IDX_2538B27272F5A1AA (channel_id), PRIMARY KEY(media_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_media_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, content LONGTEXT DEFAULT NULL, alt LONGTEXT DEFAULT NULL, link LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_AAAC4A922C2AC5D3 (translatable_id), UNIQUE INDEX sylius_cms_media_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_page (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(250) NOT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(255) DEFAULT NULL, template VARCHAR(250) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, publish_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_2C2740B277153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_page_channels (page_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_E8AF4F7FC4663E4 (page_id), INDEX IDX_E8AF4F7F72F5A1AA (channel_id), PRIMARY KEY(page_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, teaser_image_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(1000) DEFAULT NULL, meta_description VARCHAR(5000) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, teaser_title VARCHAR(255) DEFAULT NULL, teaser_content LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_6D0D401B2C2AC5D3 (translatable_id), INDEX IDX_6D0D401BF56F16CF (teaser_image_id), UNIQUE INDEX sylius_cms_page_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_section (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(250) NOT NULL, name VARCHAR(250) DEFAULT NULL, type VARCHAR(250) DEFAULT NULL, UNIQUE INDEX UNIQ_D4DD0C0777153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_section_pages (section_id INT NOT NULL, page_id INT NOT NULL, INDEX IDX_2C0728F8D823E37A (section_id), INDEX IDX_2C0728F8C4663E4 (page_id), PRIMARY KEY(section_id, page_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_section_blocks (section_id INT NOT NULL, block_id INT NOT NULL, INDEX IDX_5DE81928D823E37A (section_id), INDEX IDX_5DE81928E9ED820C (block_id), PRIMARY KEY(section_id, block_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_section_media (section_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_665F6C81D823E37A (section_id), INDEX IDX_665F6C81EA9FDD75 (media_id), PRIMARY KEY(section_id, media_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_cms_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(250) DEFAULT NULL, type VARCHAR(250) DEFAULT NULL, content_elements JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_cms_block_channels ADD CONSTRAINT FK_7026602FE9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_channels ADD CONSTRAINT FK_7026602F72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_products ADD CONSTRAINT FK_3088D8C3E9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_products ADD CONSTRAINT FK_3088D8C34584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons ADD CONSTRAINT FK_5397DD03E9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons ADD CONSTRAINT FK_5397DD03DE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons ADD CONSTRAINT FK_B4D0B7CEE9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons ADD CONSTRAINT FK_B4D0B7CEDE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration ADD CONSTRAINT FK_BB97608DE9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id)');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration ADD CONSTRAINT FK_BB97608DC4663E4 FOREIGN KEY (page_id) REFERENCES sylius_cms_page (id)');
        $this->addSql('ALTER TABLE sylius_cms_media_channels ADD CONSTRAINT FK_2538B272EA9FDD75 FOREIGN KEY (media_id) REFERENCES sylius_cms_media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_media_channels ADD CONSTRAINT FK_2538B27272F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_media_translation ADD CONSTRAINT FK_AAAC4A922C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_cms_media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_page_channels ADD CONSTRAINT FK_E8AF4F7FC4663E4 FOREIGN KEY (page_id) REFERENCES sylius_cms_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_page_channels ADD CONSTRAINT FK_E8AF4F7F72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_page_translation ADD CONSTRAINT FK_6D0D401B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_cms_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_page_translation ADD CONSTRAINT FK_6D0D401BF56F16CF FOREIGN KEY (teaser_image_id) REFERENCES sylius_cms_media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_cms_section_pages ADD CONSTRAINT FK_2C0728F8D823E37A FOREIGN KEY (section_id) REFERENCES sylius_cms_section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_section_pages ADD CONSTRAINT FK_2C0728F8C4663E4 FOREIGN KEY (page_id) REFERENCES sylius_cms_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks ADD CONSTRAINT FK_5DE81928D823E37A FOREIGN KEY (section_id) REFERENCES sylius_cms_section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks ADD CONSTRAINT FK_5DE81928E9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_section_media ADD CONSTRAINT FK_665F6C81D823E37A FOREIGN KEY (section_id) REFERENCES sylius_cms_section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_section_media ADD CONSTRAINT FK_665F6C81EA9FDD75 FOREIGN KEY (media_id) REFERENCES sylius_cms_media (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_cms_block_channels DROP FOREIGN KEY FK_7026602FE9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_channels DROP FOREIGN KEY FK_7026602F72F5A1AA');
        $this->addSql('ALTER TABLE sylius_cms_block_products DROP FOREIGN KEY FK_3088D8C3E9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_products DROP FOREIGN KEY FK_3088D8C34584665A');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons DROP FOREIGN KEY FK_5397DD03E9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons DROP FOREIGN KEY FK_5397DD03DE13F470');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons DROP FOREIGN KEY FK_B4D0B7CEE9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons DROP FOREIGN KEY FK_B4D0B7CEDE13F470');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration DROP FOREIGN KEY FK_BB97608DE9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration DROP FOREIGN KEY FK_BB97608DC4663E4');
        $this->addSql('ALTER TABLE sylius_cms_media_channels DROP FOREIGN KEY FK_2538B272EA9FDD75');
        $this->addSql('ALTER TABLE sylius_cms_media_channels DROP FOREIGN KEY FK_2538B27272F5A1AA');
        $this->addSql('ALTER TABLE sylius_cms_media_translation DROP FOREIGN KEY FK_AAAC4A922C2AC5D3');
        $this->addSql('ALTER TABLE sylius_cms_page_channels DROP FOREIGN KEY FK_E8AF4F7FC4663E4');
        $this->addSql('ALTER TABLE sylius_cms_page_channels DROP FOREIGN KEY FK_E8AF4F7F72F5A1AA');
        $this->addSql('ALTER TABLE sylius_cms_page_translation DROP FOREIGN KEY FK_6D0D401B2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_cms_page_translation DROP FOREIGN KEY FK_6D0D401BF56F16CF');
        $this->addSql('ALTER TABLE sylius_cms_section_pages DROP FOREIGN KEY FK_2C0728F8D823E37A');
        $this->addSql('ALTER TABLE sylius_cms_section_pages DROP FOREIGN KEY FK_2C0728F8C4663E4');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks DROP FOREIGN KEY FK_5DE81928D823E37A');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks DROP FOREIGN KEY FK_5DE81928E9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_section_media DROP FOREIGN KEY FK_665F6C81D823E37A');
        $this->addSql('ALTER TABLE sylius_cms_section_media DROP FOREIGN KEY FK_665F6C81EA9FDD75');
        $this->addSql('DROP TABLE sylius_cms_block');
        $this->addSql('DROP TABLE sylius_cms_block_channels');
        $this->addSql('DROP TABLE sylius_cms_block_products');
        $this->addSql('DROP TABLE sylius_cms_block_taxons');
        $this->addSql('DROP TABLE sylius_cms_block_products_in_taxons');
        $this->addSql('DROP TABLE sylius_cms_content_configuration');
        $this->addSql('DROP TABLE sylius_cms_media');
        $this->addSql('DROP TABLE sylius_cms_media_channels');
        $this->addSql('DROP TABLE sylius_cms_media_translation');
        $this->addSql('DROP TABLE sylius_cms_page');
        $this->addSql('DROP TABLE sylius_cms_page_channels');
        $this->addSql('DROP TABLE sylius_cms_page_translation');
        $this->addSql('DROP TABLE sylius_cms_section');
        $this->addSql('DROP TABLE sylius_cms_section_pages');
        $this->addSql('DROP TABLE sylius_cms_section_blocks');
        $this->addSql('DROP TABLE sylius_cms_section_media');
        $this->addSql('DROP TABLE sylius_cms_template');
    }
}
