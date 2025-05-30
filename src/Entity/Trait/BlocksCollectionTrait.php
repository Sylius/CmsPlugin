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

namespace Sylius\CmsPlugin\Entity\Trait;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\CmsPlugin\Entity\BlockInterface;

trait BlocksCollectionTrait
{
    /** @var Collection<array-key, BlockInterface> */
    protected Collection $blocks;

    public function initializeBlocksCollection(): void
    {
        $this->blocks = new ArrayCollection();
    }

    public function getBlocks(): ?Collection
    {
        return $this->blocks;
    }

    public function hasBlock(BlockInterface $block): bool
    {
        return $this->blocks->contains($block);
    }

    public function addBlock(BlockInterface $block): void
    {
        if (false === $this->hasBlock($block)) {
            $this->blocks->add($block);
        }
    }

    public function removeBlock(BlockInterface $block): void
    {
        if (true === $this->hasBlock($block)) {
            $this->blocks->removeElement($block);
        }
    }
}
