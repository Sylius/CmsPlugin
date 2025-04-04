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

namespace Sylius\CmsPlugin\Assigner;

use Sylius\CmsPlugin\Entity\TaxonAwareInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class TaxonsAssigner implements TaxonsAssignerInterface
{
    public function __construct(private TaxonRepositoryInterface $taxonRepository)
    {
    }

    public function assign(TaxonAwareInterface $taxonAware, array $taxonCodes): void
    {
        $taxons = $this->taxonRepository->findBy(['code' => $taxonCodes]);
        Assert::allIsInstanceOf($taxons, TaxonInterface::class);

        foreach ($taxons as $taxon) {
            $taxonAware->addTaxon($taxon);
        }
    }
}
