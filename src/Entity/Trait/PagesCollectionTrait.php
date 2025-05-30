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
use Sylius\CmsPlugin\Entity\PageInterface;

trait PagesCollectionTrait
{
    /** @var Collection<array-key, PageInterface> */
    protected Collection $pages;

    public function initializePagesCollection(): void
    {
        $this->pages = new ArrayCollection();
    }

    public function getPages(): ?Collection
    {
        return $this->pages;
    }

    public function hasPage(PageInterface $page): bool
    {
        return $this->pages->contains($page);
    }

    public function addPage(PageInterface $page): void
    {
        if (false === $this->hasPage($page)) {
            $this->pages->add($page);
        }
    }

    public function removePage(PageInterface $page): void
    {
        if (true === $this->hasPage($page)) {
            $this->pages->removeElement($page);
        }
    }
}
