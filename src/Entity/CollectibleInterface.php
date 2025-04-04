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

namespace Sylius\CmsPlugin\Entity;

use Doctrine\Common\Collections\Collection;

interface CollectibleInterface
{
    public function initializeCollectionsCollection(): void;

    /**
     * @return Collection|CollectionInterface[]
     */
    public function getCollections(): ?Collection;

    public function hasCollection(CollectionInterface $collection): bool;

    public function addCollection(CollectionInterface $collection): void;

    public function removeCollection(CollectionInterface $collection): void;
}
