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

use Sylius\Component\Core\Model\ProductInterface;

interface BlockProductAwareInterface
{
    public function canBeDisplayedForProduct(ProductInterface $product): bool;

    public function canBeDisplayedForProductInTaxon(ProductInterface $product): bool;
}
