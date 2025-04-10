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

namespace Tests\Sylius\CmsPlugin\Unit\Sorter;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Sorter\CollectionsSorter;

final class CollectionsSorterTest extends TestCase
{
    private CollectionsSorter $collectionsSorter;

    protected function setUp(): void
    {
        $this->collectionsSorter = new CollectionsSorter();
    }

    public function testSortsCollectionsWithOneElement(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $collectionMock->expects(self::once())->method('getCode')->willReturn('COLLECTION_CODE');
        $pageMock->expects(self::once())->method('getCollections')->willReturn(new ArrayCollection([$collectionMock]));

        self::assertSame([
            'COLLECTION_CODE' => ['collection' => $collectionMock, 0 => $pageMock],
        ], $this->collectionsSorter->sortByCollections([$pageMock]));
    }

    public function testSortsCollectionsWithMoreElements(): void
    {
        /** @var PageInterface&MockObject $page1Mock */
        $page1Mock = $this->createMock(PageInterface::class);
        /** @var PageInterface&MockObject $page2Mock */
        $page2Mock = $this->createMock(PageInterface::class);
        /** @var PageInterface&MockObject $page3Mock */
        $page3Mock = $this->createMock(PageInterface::class);
        /** @var CollectionInterface&MockObject $collection1Mock */
        $collection1Mock = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $collection2Mock */
        $collection2Mock = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $collection3Mock */
        $collection3Mock = $this->createMock(CollectionInterface::class);
        $collection1Mock->expects(self::exactly(2))->method('getCode')->willReturn('COLLECTION_1_CODE');
        $collection2Mock->expects(self::once())->method('getCode')->willReturn('COLLECTION_2_CODE');
        $collection3Mock->expects(self::exactly(2))->method('getCode')->willReturn('COLLECTION_3_CODE');
        $page1Mock->expects(self::once())->method('getCollections')->willReturn(new ArrayCollection(
            [$collection1Mock, $collection3Mock],
        ));
        $page2Mock->expects(self::once())->method('getCollections')->willReturn(new ArrayCollection([$collection3Mock]));
        $page3Mock->expects(self::once())->method('getCollections')->willReturn(new ArrayCollection(
            [$collection2Mock, $collection1Mock],
        ));

        self::assertSame([
            'COLLECTION_1_CODE' => ['collection' => $collection1Mock, 0 => $page1Mock, 1 => $page3Mock],
            'COLLECTION_3_CODE' => ['collection' => $collection3Mock, 0 => $page1Mock, 1 => $page2Mock],
            'COLLECTION_2_CODE' => ['collection' => $collection2Mock, 0 => $page3Mock],
        ], $this->collectionsSorter->sortByCollections([$page1Mock, $page2Mock, $page3Mock]));
    }

    public function testSortsCollectionsWithLessElements(): void
    {
        /** @var PageInterface&MockObject $page1Mock */
        $page1Mock = $this->createMock(PageInterface::class);
        /** @var PageInterface&MockObject $page2Mock */
        $page2Mock = $this->createMock(PageInterface::class);
        /** @var CollectionInterface&MockObject $collection1Mock */
        $collection1Mock = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $collection2Mock */
        $collection2Mock = $this->createMock(CollectionInterface::class);
        $collection1Mock->expects(self::once())->method('getCode')->willReturn('COLLECTION_1_CODE');
        $collection2Mock->expects(self::once())->method('getCode')->willReturn('COLLECTION_2_CODE');
        $page1Mock->expects(self::once())->method('getCollections')->willReturn(new ArrayCollection([$collection1Mock]));
        $page2Mock->expects(self::once())->method('getCollections')->willReturn(new ArrayCollection([$collection2Mock]));
        self::assertSame([
            'COLLECTION_1_CODE' => ['collection' => $collection1Mock, 0 => $page1Mock],
            'COLLECTION_2_CODE' => ['collection' => $collection2Mock, 0 => $page2Mock],
        ], $this->collectionsSorter->sortByCollections([$page1Mock, $page2Mock]));
    }
}
