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
use Sylius\Component\Core\Model\TaxonInterface;

interface TaxonAwareInterface
{
    public function initializeTaxonCollection(): void;

    /** @return Collection<array-key, TaxonInterface> */
    public function getTaxons(): Collection;

    public function hasTaxon(TaxonInterface $taxon): bool;

    public function addTaxon(TaxonInterface $taxon): void;

    public function removeTaxon(TaxonInterface $taxon): void;
}
