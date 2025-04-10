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

namespace Tests\Sylius\CmsPlugin\Unit\Renderer\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Renderer\Collection\CollectionBlocksRenderer;
use Sylius\CmsPlugin\Renderer\Collection\CollectionRendererInterface;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;

final class CollectionBlocksRendererTest extends TestCase
{
    /** @var ContentElementRendererStrategyInterface&MockObject */
    private MockObject $contentElementRendererStrategyMock;

    private CollectionBlocksRenderer $collectionBlocksRenderer;

    protected function setUp(): void
    {
        $this->contentElementRendererStrategyMock = $this->createMock(ContentElementRendererStrategyInterface::class);
        $this->collectionBlocksRenderer = new CollectionBlocksRenderer($this->contentElementRendererStrategyMock);
    }

    public function testImplementsCollectionRendererInterface(): void
    {
        self::assertInstanceOf(CollectionRendererInterface::class, $this->collectionBlocksRenderer);
    }

    public function testRendersBlocksFromCollection(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var BlockInterface&MockObject $block1Mock */
        $block1Mock = $this->createMock(BlockInterface::class);
        /** @var BlockInterface&MockObject $block2Mock */
        $block2Mock = $this->createMock(BlockInterface::class);

        $collectionMock->expects(self::exactly(2))->method('getBlocks')->willReturn(new ArrayCollection([$block1Mock, $block2Mock]));
        $this->contentElementRendererStrategyMock->expects(self::exactly(2))->method('render')->willReturnOnConsecutiveCalls(
            'block1_content',
            'block2_content',
        );

        self::assertSame('block1_contentblock2_content', $this->collectionBlocksRenderer->render($collectionMock));
    }

    public function testLimitsNumberOfRenderedBlocks(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var BlockInterface&MockObject $block1Mock */
        $block1Mock = $this->createMock(BlockInterface::class);
        /** @var BlockInterface&MockObject $block2Mock */
        $block2Mock = $this->createMock(BlockInterface::class);

        $collectionMock->expects(self::exactly(2))->method('getBlocks')->willReturn(new ArrayCollection([$block1Mock, $block2Mock]));
        $this->contentElementRendererStrategyMock->expects(self::once())->method('render')->willReturn('block1_content');

        self::assertSame('block1_content', $this->collectionBlocksRenderer->render($collectionMock, 1));
    }

    public function testSupportsCollectionsWithBlocks(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);

        $collectionMock->expects(self::once())->method('getBlocks')->willReturn(new ArrayCollection([$blockMock]));

        self::assertTrue($this->collectionBlocksRenderer->supports($collectionMock));
    }

    public function testDoesNotSupportEmptyCollections(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $collectionMock->expects(self::once())->method('getBlocks')->willReturn(new ArrayCollection());

        self::assertFalse($this->collectionBlocksRenderer->supports($collectionMock));
    }

    public function testThrowsExceptionWhenBlocksAreNull(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $collectionMock->expects(self::once())->method('getBlocks')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);

        $this->collectionBlocksRenderer->render($collectionMock);
    }
}
