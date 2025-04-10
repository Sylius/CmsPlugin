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

namespace Tests\Sylius\CmsPlugin\Unit\Resolver\Importer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Assigner\ProductsAssignerInterface;
use Sylius\CmsPlugin\Entity\ProductsAwareInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterProductsResolver;

final class ImporterProductsResolverTest extends TestCase
{
    /** @var ProductsAssignerInterface&MockObject */
    private MockObject $productsAssignerMock;

    private ImporterProductsResolver $importerProductsResolver;

    protected function setUp(): void
    {
        $this->productsAssignerMock = $this->createMock(ProductsAssignerInterface::class);
        $this->importerProductsResolver = new ImporterProductsResolver($this->productsAssignerMock);
    }

    public function testResolvesProductsForProductsAware(): void
    {
        /** @var ProductsAwareInterface&MockObject $productsAwareMock */
        $productsAwareMock = $this->createMock(ProductsAwareInterface::class);
        $productsRow = 'product1, product2, product3';
        $productsCodes = ['product1', 'product2', 'product3'];
        $this->productsAssignerMock->expects(self::once())->method('assign')->with($productsAwareMock, $productsCodes);
        $this->importerProductsResolver->resolve($productsAwareMock, $productsRow);
    }

    public function testSkipsResolutionWhenProductsRowIsNull(): void
    {
        /** @var ProductsAwareInterface&MockObject $productsAwareMock */
        $productsAwareMock = $this->createMock(ProductsAwareInterface::class);
        $productsRow = null;
        $this->productsAssignerMock->expects(self::never())->method('assign');
        $this->importerProductsResolver->resolve($productsAwareMock, $productsRow);
    }
}
