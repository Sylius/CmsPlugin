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

namespace Sylius\CmsPlugin\Sorter;

use Sylius\CmsPlugin\Entity\PageInterface;

interface CollectionsSorterInterface
{
    /**
     * @param array<int, PageInterface> $pages
     *
     * @return array<string, array<array-key, mixed>>
     */
    public function sortByCollections(array $pages): array;
}
