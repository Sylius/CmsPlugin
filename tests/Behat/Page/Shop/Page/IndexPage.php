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

namespace Tests\Sylius\CmsPlugin\Behat\Page\Shop\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class IndexPage extends SymfonyPage implements IndexPageInterface
{
    public function getRouteName(): string
    {
        return 'sylius_cms_shop_collections_page_index';
    }

    public function hasCollectionName(string $collectionName): bool
    {
        return $collectionName === $this->getElement('collection')->getText();
    }

    public function hasPagesNumber(int $pagesNumber): bool
    {
        $pagesNumberOnPage = count($this->getElement('pages')->findAll('css', '.cms-page'));

        return $pagesNumber === $pagesNumberOnPage;
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'collection' => '.cms-collection-name',
            'pages' => '#cms-pages',
        ]);
    }
}
