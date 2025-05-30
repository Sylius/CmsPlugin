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

use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePageInterface;
use Tests\Sylius\CmsPlugin\Behat\Behaviour\ChecksCodeImmutabilityInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface, ChecksCodeImmutabilityInterface
{
    public const IMAGE_FORM_ID = 'sylius_cms_page_translations_en_US_image';

    public function fillField(string $field, string $value): void;

    public function chooseImage(string $code): void;

    /** @return string[] */
    public function getCollections(): array;
}
