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

namespace Tests\Sylius\CmsPlugin\Unit\Importer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Importer\BlockImporter;
use Sylius\CmsPlugin\Importer\BlockImporterInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterChannelsResolverInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterCollectionsResolverInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterProductsInTaxonsResolverInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterProductsResolverInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterTaxonsResolverInterface;
use Sylius\CmsPlugin\Resolver\ResourceResolverInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BlockImporterTest extends TestCase
{
    /** @var ResourceResolverInterface&MockObject */
    private MockObject $blockResourceResolverMock;

    /** @var ImporterCollectionsResolverInterface&MockObject */
    private MockObject $importerCollectionsResolverMock;

    /** @var ImporterChannelsResolverInterface&MockObject */
    private MockObject $importerChannelsResolverMock;

    /** @var ImporterProductsResolverInterface&MockObject */
    private MockObject $importerProductsResolverMock;

    /** @var ImporterTaxonsResolverInterface&MockObject */
    private MockObject $importerTaxonsResolverMock;

    /** @var ImporterProductsInTaxonsResolverInterface&MockObject */
    private MockObject $importerProductsInTaxonsResolverMock;

    /** @var ValidatorInterface&MockObject */
    private MockObject $validatorMock;

    /** @var BlockRepositoryInterface&MockObject */
    private MockObject $blockRepositoryMock;

    private BlockImporter $blockImporter;

    protected function setUp(): void
    {
        $this->blockResourceResolverMock = $this->createMock(ResourceResolverInterface::class);
        $this->importerCollectionsResolverMock = $this->createMock(ImporterCollectionsResolverInterface::class);
        $this->importerChannelsResolverMock = $this->createMock(ImporterChannelsResolverInterface::class);
        $this->importerProductsResolverMock = $this->createMock(ImporterProductsResolverInterface::class);
        $this->importerTaxonsResolverMock = $this->createMock(ImporterTaxonsResolverInterface::class);
        $this->importerProductsInTaxonsResolverMock = $this->createMock(ImporterProductsInTaxonsResolverInterface::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->blockRepositoryMock = $this->createMock(BlockRepositoryInterface::class);
        $this->blockImporter = new BlockImporter($this->blockResourceResolverMock, $this->importerCollectionsResolverMock, $this->importerChannelsResolverMock, $this->importerProductsResolverMock, $this->importerTaxonsResolverMock, $this->importerProductsInTaxonsResolverMock, $this->validatorMock, $this->blockRepositoryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(BlockImporter::class, $this->blockImporter);
        self::assertInstanceOf(BlockImporterInterface::class, $this->blockImporter);
    }

    public function testImportsBlock(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        $row = ['name' => 'block_name', 'code' => 'block_code', 'enabled' => '1'];
        $this->blockResourceResolverMock->expects(self::once())->method('getResource')->with('block_code')->willReturn($blockMock);
        $blockMock->expects(self::once())->method('setCode')->with('block_code');
        $blockMock->expects(self::once())->method('setName')->with('block_name');
        $blockMock->expects(self::once())->method('setEnabled')->with(true);
        $this->importerCollectionsResolverMock->expects(self::once())->method('resolve')->with($blockMock, null);
        $this->importerChannelsResolverMock->expects(self::once())->method('resolve')->with($blockMock, null);
        $this->importerProductsResolverMock->expects(self::once())->method('resolve')->with($blockMock, null);
        $this->importerTaxonsResolverMock->expects(self::once())->method('resolve')->with($blockMock, null);
        $this->importerProductsInTaxonsResolverMock->expects(self::once())->method('resolve')->with($blockMock, null);
        $this->validatorMock->expects(self::once())->method('validate')->with($blockMock, null, ['cms'])->willReturn(new ConstraintViolationList());
        $this->blockRepositoryMock->expects(self::once())->method('add')->with($blockMock);
        $this->blockImporter->import($row);
    }

    public function testGetsResourceCode(): void
    {
        self::assertSame('block', $this->blockImporter->getResourceCode());
    }
}
