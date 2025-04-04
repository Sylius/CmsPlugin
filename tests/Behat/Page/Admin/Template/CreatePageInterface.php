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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Admin\Template;

interface CreatePageInterface
{
    public function fillField(string $field, string $value): void;

    public function fillName(string $name): void;

    public function chooseType(string $name): void;

    public function clickOnAddContentElementButton(): void;

    public function selectContentElement(string $contentElement): void;
}
