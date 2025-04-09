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
use Sylius\CmsPlugin\Assigner\ProductsInTaxonsAssignerInterface;
use Sylius\CmsPlugin\Entity\ProductsInTaxonsAwareInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterProductsInTaxonsResolver;

final class ImporterProductsInTaxonsResolverTest extends TestCase
{
    /** @var ProductsInTaxonsAssignerInterface&MockObject */
    private MockObject $productsInTaxonsAssignerMock;

    private ImporterProductsInTaxonsResolver $importerProductsInTaxonsResolver;

    protected function setUp(): void
    {
        $this->productsInTaxonsAssignerMock = $this->createMock(ProductsInTaxonsAssignerInterface::class);
        $this->importerProductsInTaxonsResolver = new ImporterProductsInTaxonsResolver($this->productsInTaxonsAssignerMock);
    }

    public function testResolvesTaxonsForProductsInTaxonsAwareEntity(): void
    {
        /** @var ProductsInTaxonsAwareInterface&MockObject $productsInTaxonsAwareMock */
        $productsInTaxonsAwareMock = $this->createMock(ProductsInTaxonsAwareInterface::class);
        $taxonsRow = 'taxon_code_1, taxon_code_2';
        $this->productsInTaxonsAssignerMock->expects(self::once())->method('assign')->with($productsInTaxonsAwareMock, ['taxon_code_1', 'taxon_code_2']);
        $this->importerProductsInTaxonsResolver->resolve($productsInTaxonsAwareMock, $taxonsRow);
    }

    public function testDoesNotAssignTaxonsWhenTaxonsRowIsNull(): void
    {
        /** @var ProductsInTaxonsAwareInterface&MockObject $productsInTaxonsAwareMock */
        $productsInTaxonsAwareMock = $this->createMock(ProductsInTaxonsAwareInterface::class);
        $this->productsInTaxonsAssignerMock->expects(self::never())->method('assign')->with($productsInTaxonsAwareMock, []);
        $this->importerProductsInTaxonsResolver->resolve($productsInTaxonsAwareMock, null);
    }
}
