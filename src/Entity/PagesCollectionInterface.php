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

interface PagesCollectionInterface
{
    public function initializePagesCollection(): void;

    /** @return Collection<array-key, PageInterface> */
    public function getPages(): ?Collection;

    public function hasPage(PageInterface $page): bool;

    public function addPage(PageInterface $page): void;

    public function removePage(PageInterface $page): void;
}
