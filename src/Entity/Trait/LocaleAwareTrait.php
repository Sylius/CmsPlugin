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
use Sylius\Component\Locale\Model\LocaleInterface;

trait LocaleAwareTrait
{
    /** @var Collection<array-key, LocaleInterface> */
    protected Collection $locales;

    public function initializeLocalesCollection(): void
    {
        $this->locales = new ArrayCollection();
    }

    public function getLocales(): Collection
    {
        return $this->locales;
    }

    public function hasLocale(LocaleInterface $locale): bool
    {
        return $this->locales->contains($locale);
    }

    public function addLocale(LocaleInterface $locale): void
    {
        if (!$this->hasLocale($locale)) {
            $this->locales->add($locale);
        }
    }

    public function removeLocale(LocaleInterface $locale): void
    {
        if ($this->hasLocale($locale)) {
            $this->locales->removeElement($locale);
        }
    }
}
