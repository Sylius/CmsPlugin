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
use Sylius\CmsPlugin\Entity\CollectibleInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Fixture\Assigner\CollectionsAssigner;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface;

final class CollectionsAssignerTest extends TestCase
{
    /** @var MockObject&CollectionRepositoryInterface<CollectionInterface> */
    private MockObject $collectionRepository;

    private CollectionsAssigner $collectionsAssigner;

    protected function setUp(): void
    {
        $this->collectionRepository = $this->createMock(CollectionRepositoryInterface::class);

        $this->collectionsAssigner = new CollectionsAssigner($this->collectionRepository);
    }

    public function testImplementsCollectionsAssignerInterface(): void
    {
        self::assertInstanceOf(CollectionsAssigner::class, $this->collectionsAssigner);
    }

    public function testAssignsCollections(): void
    {
        /** @var CollectibleInterface&MockObject $collectionsAware */
        $collectionsAware = $this->createMock(CollectibleInterface::class);
        /** @var CollectionInterface&MockObject $collection1 */
        $collection1 = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $collection2 */
        $collection2 = $this->createMock(CollectionInterface::class);

        $this->collectionRepository
            ->expects(self::once())
            ->method('findBy')
            ->with(['code' => ['collection_code_1', 'collection_code_2']])
            ->willReturn([$collection1, $collection2])
        ;

        $collectionsAware->expects(self::exactly(2))->method('addCollection');

        $this->collectionsAssigner->assign($collectionsAware, ['collection_code_1', 'collection_code_2']);
    }
}
