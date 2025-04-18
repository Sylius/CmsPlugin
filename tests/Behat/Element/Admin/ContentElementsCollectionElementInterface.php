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

namespace Tests\Sylius\CmsPlugin\Behat\Element\Admin;

interface ContentElementsCollectionElementInterface
{
    public function selectTemplate(string $templateName): void;

    public function applyTemplate(): void;

    public function hasContentElement(string $type): bool;

    /** @param string|array<array-key|string, string> $content */
    public function hasContentElementWithContent(string $type, array|string $content): bool;

    public function getContentElementsCount(): int;

    /** @param string|array<array-key|string, string> $content */
    public function updateContentElementOfType(string $type, array|string $content): void;

    /** @param string|array<array-key|string, string> $content */
    public function addContentElementOfTypeWithContent(string $type, array|string $content): void;

    public function removeContentElement(string $type): void;
}
