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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Admin\Page;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ContainsErrorInterface;

interface CreatePageInterface extends BaseCreatePageInterface, ContainsErrorInterface
{
    public const IMAGE_FORM_ID = 'sylius_cms_page_translations_en_US_image';

    public function fillField(string $field, string $value): void;

    public function chooseImage(string $code): void;

    public function fillCode(string $code): void;

    public function fillName(string $name): void;

    public function fillSlug(string $slug): void;

    public function fillMetaKeywords(string $metaKeywords): void;

    public function fillMetaDescription(string $metaDescription): void;

    public function fillContent(string $content): void;

    /** @return string[] */
    public function getCollections(): array;

    /** @param string[] $collectionsNames */
    public function associateCollections(array $collectionsNames): void;

    public function selectTemplate(string $templateName): void;

    public function selectChannel(string $code): void;
}
