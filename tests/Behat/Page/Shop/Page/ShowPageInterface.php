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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Shop\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface ShowPageInterface extends SymfonyPageInterface
{
    public function hasName(string $name): bool;

    public function hasContent(string $content): bool;

    public function hasProducts(array $productsNames): bool;

    public function hasCollections(array $collectionNames): bool;

    public function hasPageLink(string $linkName): bool;

    public function hasPageImage(): bool;

    public function hasTitle(string $title): bool;

    public function hasCustomLayoutCode(): bool;
}
