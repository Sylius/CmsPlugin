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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePageInterface as BaseHomePageInterface;

interface HomePageInterface extends BaseHomePageInterface
{
    public function hasImageBlock(): bool;

    public function hasBlockWithContent(string $content): bool;
}
