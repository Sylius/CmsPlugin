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

namespace Sylius\CmsPlugin\Entity;

use Doctrine\Common\Collections\Collection;

interface BlocksCollectionInterface
{
    public function initializeBlocksCollection(): void;

    /** @return Collection<array-key, BlockInterface> */
    public function getBlocks(): ?Collection;

    public function hasBlock(BlockInterface $block): bool;

    public function addBlock(BlockInterface $block): void;

    public function removeBlock(BlockInterface $block): void;
}
