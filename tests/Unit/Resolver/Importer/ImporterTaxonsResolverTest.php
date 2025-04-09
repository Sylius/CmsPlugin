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
use Sylius\CmsPlugin\Assigner\TaxonsAssignerInterface;
use Sylius\CmsPlugin\Entity\TaxonAwareInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterTaxonsResolver;

final class ImporterTaxonsResolverTest extends TestCase
{
    /** @var TaxonsAssignerInterface&MockObject */
    private MockObject $taxonsAssignerMock;

    private ImporterTaxonsResolver $importerTaxonsResolver;

    protected function setUp(): void
    {
        $this->taxonsAssignerMock = $this->createMock(TaxonsAssignerInterface::class);
        $this->importerTaxonsResolver = new ImporterTaxonsResolver($this->taxonsAssignerMock);
    }

    public function testResolvesTaxonsForTaxonAwareEntity(): void
    {
        /** @var TaxonAwareInterface&MockObject $taxonsAwareMock */
        $taxonsAwareMock = $this->createMock(TaxonAwareInterface::class);
        $taxonsRow = 'taxon_code_1, taxon_code_2';
        $this->taxonsAssignerMock->expects(self::once())->method('assign')->with($taxonsAwareMock, ['taxon_code_1', 'taxon_code_2']);
        $this->importerTaxonsResolver->resolve($taxonsAwareMock, $taxonsRow);
    }

    public function testDoesNotAssignTaxonsWhenTaxonsRowIsNull(): void
    {
        /** @var TaxonAwareInterface&MockObject $taxonsAwareMock */
        $taxonsAwareMock = $this->createMock(TaxonAwareInterface::class);
        $this->taxonsAssignerMock->expects(self::never())->method('assign')->with($taxonsAwareMock, []);
        $this->importerTaxonsResolver->resolve($taxonsAwareMock, null);
    }
}
