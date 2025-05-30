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
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final class ProductsAssigner implements ProductsAssignerInterface
{
    /** @param ProductRepositoryInterface<ProductInterface> $productRepository */
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function assign(ProductsAwareInterface $productsAware, array $productsCodes): void
    {
        $products = $this->productRepository->findBy(['code' => $productsCodes]);

        foreach ($products as $product) {
            $productsAware->addProduct($product);
        }
    }
}
