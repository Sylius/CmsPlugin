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
use Sylius\CmsPlugin\Assigner\ProductsInTaxonsAssigner;
use Sylius\CmsPlugin\Entity\ProductsInTaxonsAwareInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class ProductsInTaxonsAssignerTest extends TestCase
{
    /** @var MockObject&TaxonRepositoryInterface<TaxonInterface> */
    private MockObject $taxonRepository;

    private ProductsInTaxonsAssigner $productsInTaxonsAssigner;

    protected function setUp(): void
    {
        $this->taxonRepository = $this->createMock(TaxonRepositoryInterface::class);

        $this->productsInTaxonsAssigner = new ProductsInTaxonsAssigner($this->taxonRepository);
    }

    public function testImplementsProductsInTaxonsAssignerInterface(): void
    {
        self::assertInstanceOf(ProductsInTaxonsAssigner::class, $this->productsInTaxonsAssigner);
    }

    public function testAssignsTaxonsToProductsInTaxonsAwareEntity(): void
    {
        /** @var ProductsInTaxonsAwareInterface&MockObject $productsInTaxonsAware */
        $productsInTaxonsAware = $this->createMock(ProductsInTaxonsAwareInterface::class);
        /** @var TaxonInterface&MockObject $taxon1 */
        $taxon1 = $this->createMock(TaxonInterface::class);
        /** @var TaxonInterface&MockObject $taxon2 */
        $taxon2 = $this->createMock(TaxonInterface::class);

        $this->taxonRepository
            ->expects(self::once())
            ->method('findBy')
            ->with(['code' => ['taxon_code_1', 'taxon_code_2']])
            ->willReturn([$taxon1, $taxon2])
        ;

        $productsInTaxonsAware->expects(self::exactly(2))->method('addProductsInTaxon');

        $this->productsInTaxonsAssigner->assign($productsInTaxonsAware, ['taxon_code_1', 'taxon_code_2']);
    }
}
