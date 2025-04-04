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

interface UpdatePageInterface
{
    public function hasContentElement(string $contentElement): bool;

    public function hasOnlyContentElement(string $contentElement): bool;

    public function fillName(string $name): void;

    public function deleteContentElement(string $name): void;
}
