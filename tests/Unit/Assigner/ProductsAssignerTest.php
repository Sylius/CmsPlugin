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

namespace Tests\Sylius\CmsPlugin\Unit\Assigner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Assigner\ProductsAssigner;
use Sylius\CmsPlugin\Entity\ProductsAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final class ProductsAssignerTest extends TestCase
{
    /** @var MockObject&ProductRepositoryInterface<ProductInterface> */
    private MockObject $productRepository;

    private ProductsAssigner $productsAssigner;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);

        $this->productsAssigner = new ProductsAssigner($this->productRepository);
    }

    public function testImplementsProductsAssignerInterface(): void
    {
        self::assertInstanceOf(ProductsAssigner::class, $this->productsAssigner);
    }

    public function testAssignsProducts(): void
    {
        /** @var ProductsAwareInterface&MockObject $productsAware */
        $productsAware = $this->createMock(ProductsAwareInterface::class);
        /** @var ProductInterface&MockObject $mugProduct */
        $mugProduct = $this->createMock(ProductInterface::class);
        /** @var ProductInterface&MockObject $tshirtProduct */
        $tshirtProduct = $this->createMock(ProductInterface::class);

        $this->productRepository
            ->expects(self::once())
            ->method('findBy')
            ->with(['code' => ['mug', 't-shirt']])
            ->willReturn([$mugProduct, $tshirtProduct])
        ;

        $productsAware->expects(self::exactly(2))->method('addProduct');

        $this->productsAssigner->assign($productsAware, ['mug', 't-shirt']);
    }
}
