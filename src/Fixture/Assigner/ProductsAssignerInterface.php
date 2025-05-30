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

namespace Sylius\CmsPlugin\Fixture\Assigner;

use Sylius\CmsPlugin\Entity\ProductsAwareInterface;

interface ProductsAssignerInterface
{
    /** @param array<string> $productsCodes */
    public function assign(ProductsAwareInterface $productsAware, array $productsCodes): void;
}
