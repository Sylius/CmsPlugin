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
use Sylius\Component\Core\Model\ProductInterface;

interface ProductsAwareInterface
{
    public function initializeProductsCollection(): void;

    /** @return Collection<array-key, ProductInterface> */
    public function getProducts(): Collection;

    public function hasProduct(ProductInterface $product): bool;

    public function addProduct(ProductInterface $product): void;

    public function removeProduct(ProductInterface $product): void;
}
