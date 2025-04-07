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

use Sylius\CmsPlugin\Entity\ProductsInTaxonsAwareInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class ProductsInTaxonsAssigner implements ProductsInTaxonsAssignerInterface
{
    /** @param TaxonRepositoryInterface<TaxonInterface> $taxonRepository */
    public function __construct(private TaxonRepositoryInterface $taxonRepository)
    {
    }

    public function assign(ProductsInTaxonsAwareInterface $productsInTaxonsAware, array $taxonCodes): void
    {
        $taxons = $this->taxonRepository->findBy(['code' => $taxonCodes]);

        foreach ($taxons as $taxon) {
            $productsInTaxonsAware->addProductsInTaxon($taxon);
        }
    }
}
