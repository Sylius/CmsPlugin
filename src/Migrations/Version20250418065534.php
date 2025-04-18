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
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration;

final class Version20250418065534 extends AbstractPostgreSQLMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE sylius_cms_block_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_content_configuration_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_media_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_page_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_page_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_section_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_cms_template_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sylius_cms_block (id INT NOT NULL, code VARCHAR(64) NOT NULL, name VARCHAR(250) DEFAULT NULL, template VARCHAR(250) DEFAULT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D2248BC77153098 ON sylius_cms_block (code)');
        $this->addSql('CREATE TABLE sylius_cms_block_channels (block_id INT NOT NULL, channel_id INT NOT NULL, PRIMARY KEY(block_id, channel_id))');
        $this->addSql('CREATE INDEX IDX_7026602FE9ED820C ON sylius_cms_block_channels (block_id)');
        $this->addSql('CREATE INDEX IDX_7026602F72F5A1AA ON sylius_cms_block_channels (channel_id)');
        $this->addSql('CREATE TABLE sylius_cms_block_products (block_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(block_id, product_id))');
        $this->addSql('CREATE INDEX IDX_3088D8C3E9ED820C ON sylius_cms_block_products (block_id)');
        $this->addSql('CREATE INDEX IDX_3088D8C34584665A ON sylius_cms_block_products (product_id)');
        $this->addSql('CREATE TABLE sylius_cms_block_taxons (block_id INT NOT NULL, taxon_id INT NOT NULL, PRIMARY KEY(block_id, taxon_id))');
        $this->addSql('CREATE INDEX IDX_5397DD03E9ED820C ON sylius_cms_block_taxons (block_id)');
        $this->addSql('CREATE INDEX IDX_5397DD03DE13F470 ON sylius_cms_block_taxons (taxon_id)');
        $this->addSql('CREATE TABLE sylius_cms_block_products_in_taxons (block_id INT NOT NULL, taxon_id INT NOT NULL, PRIMARY KEY(block_id, taxon_id))');
        $this->addSql('CREATE INDEX IDX_B4D0B7CEE9ED820C ON sylius_cms_block_products_in_taxons (block_id)');
        $this->addSql('CREATE INDEX IDX_B4D0B7CEDE13F470 ON sylius_cms_block_products_in_taxons (taxon_id)');
        $this->addSql('CREATE TABLE sylius_cms_content_configuration (id INT NOT NULL, block_id INT DEFAULT NULL, page_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration JSONB NOT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BB97608DE9ED820C ON sylius_cms_content_configuration (block_id)');
        $this->addSql('CREATE INDEX IDX_BB97608DC4663E4 ON sylius_cms_content_configuration (page_id)');
        $this->addSql('CREATE TABLE sylius_cms_media (id INT NOT NULL, code VARCHAR(250) NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(250) NOT NULL, path VARCHAR(250) NOT NULL, mime_type VARCHAR(250) DEFAULT NULL, enabled BOOLEAN NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74157E9277153098 ON sylius_cms_media (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74157E92B548B0F ON sylius_cms_media (path)');
        $this->addSql('CREATE TABLE sylius_cms_media_channels (media_id INT NOT NULL, channel_id INT NOT NULL, PRIMARY KEY(media_id, channel_id))');
        $this->addSql('CREATE INDEX IDX_2538B272EA9FDD75 ON sylius_cms_media_channels (media_id)');
        $this->addSql('CREATE INDEX IDX_2538B27272F5A1AA ON sylius_cms_media_channels (channel_id)');
        $this->addSql('CREATE TABLE sylius_cms_media_translation (id INT NOT NULL, translatable_id INT NOT NULL, content TEXT DEFAULT NULL, alt TEXT DEFAULT NULL, link TEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AAAC4A922C2AC5D3 ON sylius_cms_media_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX sylius_cms_media_translation_uniq_trans ON sylius_cms_media_translation (translatable_id, locale)');
        $this->addSql('CREATE TABLE sylius_cms_page (id INT NOT NULL, code VARCHAR(250) NOT NULL, enabled BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, template VARCHAR(250) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, publish_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C2740B277153098 ON sylius_cms_page (code)');
        $this->addSql('COMMENT ON COLUMN sylius_cms_page.publish_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sylius_cms_page_channels (page_id INT NOT NULL, channel_id INT NOT NULL, PRIMARY KEY(page_id, channel_id))');
        $this->addSql('CREATE INDEX IDX_E8AF4F7FC4663E4 ON sylius_cms_page_channels (page_id)');
        $this->addSql('CREATE INDEX IDX_E8AF4F7F72F5A1AA ON sylius_cms_page_channels (channel_id)');
        $this->addSql('CREATE TABLE sylius_cms_page_translation (id INT NOT NULL, translatable_id INT NOT NULL, teaser_image_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(1000) DEFAULT NULL, meta_description VARCHAR(5000) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, teaser_title VARCHAR(255) DEFAULT NULL, teaser_content TEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D0D401B2C2AC5D3 ON sylius_cms_page_translation (translatable_id)');
        $this->addSql('CREATE INDEX IDX_6D0D401BF56F16CF ON sylius_cms_page_translation (teaser_image_id)');
        $this->addSql('CREATE UNIQUE INDEX sylius_cms_page_translation_uniq_trans ON sylius_cms_page_translation (translatable_id, locale)');
        $this->addSql('CREATE TABLE sylius_cms_section (id INT NOT NULL, code VARCHAR(250) NOT NULL, name VARCHAR(250) DEFAULT NULL, type VARCHAR(250) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4DD0C0777153098 ON sylius_cms_section (code)');
        $this->addSql('CREATE TABLE sylius_cms_section_pages (section_id INT NOT NULL, page_id INT NOT NULL, PRIMARY KEY(section_id, page_id))');
        $this->addSql('CREATE INDEX IDX_2C0728F8D823E37A ON sylius_cms_section_pages (section_id)');
        $this->addSql('CREATE INDEX IDX_2C0728F8C4663E4 ON sylius_cms_section_pages (page_id)');
        $this->addSql('CREATE TABLE sylius_cms_section_blocks (section_id INT NOT NULL, block_id INT NOT NULL, PRIMARY KEY(section_id, block_id))');
        $this->addSql('CREATE INDEX IDX_5DE81928D823E37A ON sylius_cms_section_blocks (section_id)');
        $this->addSql('CREATE INDEX IDX_5DE81928E9ED820C ON sylius_cms_section_blocks (block_id)');
        $this->addSql('CREATE TABLE sylius_cms_section_media (section_id INT NOT NULL, media_id INT NOT NULL, PRIMARY KEY(section_id, media_id))');
        $this->addSql('CREATE INDEX IDX_665F6C81D823E37A ON sylius_cms_section_media (section_id)');
        $this->addSql('CREATE INDEX IDX_665F6C81EA9FDD75 ON sylius_cms_section_media (media_id)');
        $this->addSql('CREATE TABLE sylius_cms_template (id INT NOT NULL, name VARCHAR(250) DEFAULT NULL, type VARCHAR(250) DEFAULT NULL, content_elements JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE sylius_cms_block_channels ADD CONSTRAINT FK_7026602FE9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_channels ADD CONSTRAINT FK_7026602F72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_products ADD CONSTRAINT FK_3088D8C3E9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_products ADD CONSTRAINT FK_3088D8C34584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons ADD CONSTRAINT FK_5397DD03E9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons ADD CONSTRAINT FK_5397DD03DE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons ADD CONSTRAINT FK_B4D0B7CEE9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons ADD CONSTRAINT FK_B4D0B7CEDE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration ADD CONSTRAINT FK_BB97608DE9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration ADD CONSTRAINT FK_BB97608DC4663E4 FOREIGN KEY (page_id) REFERENCES sylius_cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_media_channels ADD CONSTRAINT FK_2538B272EA9FDD75 FOREIGN KEY (media_id) REFERENCES sylius_cms_media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_media_channels ADD CONSTRAINT FK_2538B27272F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_media_translation ADD CONSTRAINT FK_AAAC4A922C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_cms_media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_page_channels ADD CONSTRAINT FK_E8AF4F7FC4663E4 FOREIGN KEY (page_id) REFERENCES sylius_cms_page (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_page_channels ADD CONSTRAINT FK_E8AF4F7F72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_page_translation ADD CONSTRAINT FK_6D0D401B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_cms_page (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_page_translation ADD CONSTRAINT FK_6D0D401BF56F16CF FOREIGN KEY (teaser_image_id) REFERENCES sylius_cms_media (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_section_pages ADD CONSTRAINT FK_2C0728F8D823E37A FOREIGN KEY (section_id) REFERENCES sylius_cms_section (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_section_pages ADD CONSTRAINT FK_2C0728F8C4663E4 FOREIGN KEY (page_id) REFERENCES sylius_cms_page (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks ADD CONSTRAINT FK_5DE81928D823E37A FOREIGN KEY (section_id) REFERENCES sylius_cms_section (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks ADD CONSTRAINT FK_5DE81928E9ED820C FOREIGN KEY (block_id) REFERENCES sylius_cms_block (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_section_media ADD CONSTRAINT FK_665F6C81D823E37A FOREIGN KEY (section_id) REFERENCES sylius_cms_section (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_cms_section_media ADD CONSTRAINT FK_665F6C81EA9FDD75 FOREIGN KEY (media_id) REFERENCES sylius_cms_media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE sylius_cms_block_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_content_configuration_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_media_translation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_page_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_page_translation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_section_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_cms_template_id_seq CASCADE');
        $this->addSql('ALTER TABLE sylius_cms_block_channels DROP CONSTRAINT FK_7026602FE9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_channels DROP CONSTRAINT FK_7026602F72F5A1AA');
        $this->addSql('ALTER TABLE sylius_cms_block_products DROP CONSTRAINT FK_3088D8C3E9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_products DROP CONSTRAINT FK_3088D8C34584665A');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons DROP CONSTRAINT FK_5397DD03E9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_taxons DROP CONSTRAINT FK_5397DD03DE13F470');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons DROP CONSTRAINT FK_B4D0B7CEE9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_block_products_in_taxons DROP CONSTRAINT FK_B4D0B7CEDE13F470');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration DROP CONSTRAINT FK_BB97608DE9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_content_configuration DROP CONSTRAINT FK_BB97608DC4663E4');
        $this->addSql('ALTER TABLE sylius_cms_media_channels DROP CONSTRAINT FK_2538B272EA9FDD75');
        $this->addSql('ALTER TABLE sylius_cms_media_channels DROP CONSTRAINT FK_2538B27272F5A1AA');
        $this->addSql('ALTER TABLE sylius_cms_media_translation DROP CONSTRAINT FK_AAAC4A922C2AC5D3');
        $this->addSql('ALTER TABLE sylius_cms_page_channels DROP CONSTRAINT FK_E8AF4F7FC4663E4');
        $this->addSql('ALTER TABLE sylius_cms_page_channels DROP CONSTRAINT FK_E8AF4F7F72F5A1AA');
        $this->addSql('ALTER TABLE sylius_cms_page_translation DROP CONSTRAINT FK_6D0D401B2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_cms_page_translation DROP CONSTRAINT FK_6D0D401BF56F16CF');
        $this->addSql('ALTER TABLE sylius_cms_section_pages DROP CONSTRAINT FK_2C0728F8D823E37A');
        $this->addSql('ALTER TABLE sylius_cms_section_pages DROP CONSTRAINT FK_2C0728F8C4663E4');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks DROP CONSTRAINT FK_5DE81928D823E37A');
        $this->addSql('ALTER TABLE sylius_cms_section_blocks DROP CONSTRAINT FK_5DE81928E9ED820C');
        $this->addSql('ALTER TABLE sylius_cms_section_media DROP CONSTRAINT FK_665F6C81D823E37A');
        $this->addSql('ALTER TABLE sylius_cms_section_media DROP CONSTRAINT FK_665F6C81EA9FDD75');
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
