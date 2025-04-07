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

interface ContentElementsAwareInterface
{
    public function initializeContentElementsCollection(): void;

    /** @return Collection<array-key, ContentConfigurationInterface> */
    public function getContentElements(): Collection;

    public function hasContentElement(ContentConfigurationInterface $contentElement): bool;

    public function addContentElement(ContentConfigurationInterface $contentElement): void;

    public function removeContentElement(ContentConfigurationInterface $contentElement): void;
}
