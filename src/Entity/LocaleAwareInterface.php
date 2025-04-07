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
use Sylius\Component\Locale\Model\LocaleInterface;

interface LocaleAwareInterface
{
    public function initializeLocalesCollection(): void;

    /** @return Collection<array-key, LocaleInterface> */
    public function getLocales(): Collection;

    public function hasLocale(LocaleInterface $locale): bool;

    public function addLocale(LocaleInterface $locale): void;

    public function removeLocale(LocaleInterface $locale): void;
}
