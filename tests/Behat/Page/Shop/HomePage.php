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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePage as BaseHomePage;

class HomePage extends BaseHomePage implements HomePageInterface
{
    public function hasImageBlock(): bool
    {
        return $this->getElement('image_block')->has('css', 'img');
    }

    public function hasBlockWithContent(string $content): bool
    {
        return $content === $this->getElement('content')->getText();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'image_block' => '.cms-image-block',
            'content' => '.cms-block',
        ]);
    }
}
