<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240918091924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration adds a template column to the sylius_cms_block and sylius_cms_page table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_cms_block ADD template VARCHAR(250) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_cms_page ADD template VARCHAR(250) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_cms_block DROP template');
        $this->addSql('ALTER TABLE sylius_cms_page DROP template');
    }
}
