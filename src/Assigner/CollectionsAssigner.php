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

namespace Sylius\CmsPlugin\Assigner;

use Sylius\CmsPlugin\Entity\CollectibleInterface;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface;

final class CollectionsAssigner implements CollectionsAssignerInterface
{
    public function __construct(private CollectionRepositoryInterface $collectionRepository)
    {
    }

    public function assign(CollectibleInterface $collectionsAware, array $collectionsCodes): void
    {
        $collections = $this->collectionRepository->findBy(['code' => $collectionsCodes]);

        foreach ($collections as $collection) {
            $collectionsAware->addCollection($collection);
        }
    }
}
