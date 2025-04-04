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

namespace Sylius\CmsPlugin\Resolver\Importer;

use Sylius\CmsPlugin\Assigner\ProductsInTaxonsAssignerInterface;
use Sylius\CmsPlugin\Entity\ProductsInTaxonsAwareInterface;

final class ImporterProductsInTaxonsResolver implements ImporterProductsInTaxonsResolverInterface
{
    public function __construct(private ProductsInTaxonsAssignerInterface $productsInTaxonsAssigner)
    {
    }

    public function resolve(ProductsInTaxonsAwareInterface $productsInTaxonsAware, ?string $taxonsRow): void
    {
        if (null === $taxonsRow) {
            return;
        }

        $taxonsCodes = explode(',', $taxonsRow);
        $taxonsCodes = array_map(static function (string $element): string {
            return trim($element);
        }, $taxonsCodes);

        $this->productsInTaxonsAssigner->assign($productsInTaxonsAware, $taxonsCodes);
    }
}
