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

namespace Tests\Sylius\CmsPlugin\Unit\Fixture\Assigner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\TaxonAwareInterface;
use Sylius\CmsPlugin\Fixture\Assigner\TaxonsAssigner;
use Sylius\CmsPlugin\Fixture\Assigner\TaxonsAssignerInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonsAssignerTest extends TestCase
{
    /** @var MockObject&TaxonRepositoryInterface<TaxonInterface> */
    private MockObject $taxonRepository;

    private TaxonsAssigner $taxonsAssigner;

    protected function setUp(): void
    {
        $this->taxonRepository = $this->createMock(TaxonRepositoryInterface::class);

        $this->taxonsAssigner = new TaxonsAssigner($this->taxonRepository);
    }

    public function testImplementsTaxonsAssignerInterface(): void
    {
        self::assertInstanceOf(TaxonsAssignerInterface::class, $this->taxonsAssigner);
    }

    public function testAssignsTaxons(): void
    {
        /** @var TaxonAwareInterface&MockObject $taxonsAware */
        $taxonsAware = $this->createMock(TaxonAwareInterface::class);
        /** @var TaxonInterface&MockObject $mugsTaxon */
        $mugsTaxon = $this->createMock(TaxonInterface::class);
        /** @var TaxonInterface&MockObject $stickersTaxon */
        $stickersTaxon = $this->createMock(TaxonInterface::class);

        $this->taxonRepository
            ->expects(self::once())
            ->method('findBy')
            ->with(['code' => ['mugs', 'stickers']])
            ->willReturn([$mugsTaxon, $stickersTaxon])
        ;

        $taxonsAware->expects(self::exactly(2))->method('addTaxon');

        $this->taxonsAssigner->assign($taxonsAware, ['mugs', 'stickers']);
    }
}
