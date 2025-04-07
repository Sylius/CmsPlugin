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

interface MediaCollectionInterface
{
    public function initializeMediaCollection(): void;

    /** @return Collection<array-key, MediaInterface> */
    public function getMedia(): ?Collection;

    public function hasMedium(MediaInterface $media): bool;

    public function addMedium(MediaInterface $media): void;

    public function removeMedium(MediaInterface $media): void;
}
