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
use Sylius\CmsPlugin\Assigner\CollectionsAssignerInterface;
use Sylius\CmsPlugin\Entity\CollectibleInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterCollectionsResolver;

final class ImporterCollectionsResolverTest extends TestCase
{
    /** @var CollectionsAssignerInterface&MockObject */
    private MockObject $collectionsAssignerMock;

    private ImporterCollectionsResolver $importerCollectionsResolver;

    protected function setUp(): void
    {
        $this->collectionsAssignerMock = $this->createMock(CollectionsAssignerInterface::class);
        $this->importerCollectionsResolver = new ImporterCollectionsResolver($this->collectionsAssignerMock);
    }

    public function testResolvesCollectionsForCollectionableEntity(): void
    {
        /** @var CollectibleInterface&MockObject $collectionableMock */
        $collectionableMock = $this->createMock(CollectibleInterface::class);
        $collectionsRow = 'collection1, collection2, collection3';
        $collectionsCodes = ['collection1', 'collection2', 'collection3'];
        $this->collectionsAssignerMock->expects(self::once())->method('assign')->with($collectionableMock, $collectionsCodes);
        $this->importerCollectionsResolver->resolve($collectionableMock, $collectionsRow);
    }

    public function testSkipsResolutionWhenCollectionsRowIsNull(): void
    {
        /** @var CollectibleInterface&MockObject $collectionableMock */
        $collectionableMock = $this->createMock(CollectibleInterface::class);
        $collectionsRow = null;
        $this->collectionsAssignerMock->expects(self::never())->method('assign');
        $this->importerCollectionsResolver->resolve($collectionableMock, $collectionsRow);
    }
}
