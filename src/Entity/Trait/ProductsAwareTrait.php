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
use Sylius\Component\Core\Model\ProductInterface;

trait ProductsAwareTrait
{
    /** @var Collection<array-key, ProductInterface> */
    protected Collection $products;

    public function initializeProductsCollection(): void
    {
        $this->products = new ArrayCollection();
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    public function addProduct(ProductInterface $product): void
    {
        if (false === $this->hasProduct($product)) {
            $this->products->add($product);
        }
    }

    public function removeProduct(ProductInterface $product): void
    {
        if (true === $this->hasProduct($product)) {
            $this->products->removeElement($product);
        }
    }

    public function canBeDisplayedForProduct(ProductInterface $product): bool
    {
        return $this->hasProduct($product);
    }
}
