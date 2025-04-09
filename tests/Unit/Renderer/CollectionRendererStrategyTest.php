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

namespace Tests\Sylius\CmsPlugin\Unit\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Renderer\Collection\CollectionRendererInterface;
use Sylius\CmsPlugin\Renderer\CollectionRendererStrategy;
use Sylius\CmsPlugin\Renderer\CollectionRendererStrategyInterface;

final class CollectionRendererStrategyTest extends TestCase
{
    /** @var CollectionRendererInterface&MockObject */
    private MockObject $renderer1Mock;

    /** @var CollectionRendererInterface&MockObject */
    private MockObject $renderer2Mock;

    private CollectionRendererStrategy $collectionRendererStrategy;

    protected function setUp(): void
    {
        $this->renderer1Mock = $this->createMock(CollectionRendererInterface::class);
        $this->renderer2Mock = $this->createMock(CollectionRendererInterface::class);
        $this->collectionRendererStrategy = new CollectionRendererStrategy([$this->renderer1Mock, $this->renderer2Mock]);
    }

    public function testImplementsCollectionRendererStrategyInterface(): void
    {
        self::assertInstanceOf(CollectionRendererStrategyInterface::class, $this->collectionRendererStrategy);
    }

    public function testRendersCollectionUsingSupportedRenderer(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $this->renderer1Mock->expects(self::once())->method('supports')->with($collectionMock)->willReturn(false);
        $this->renderer2Mock->expects(self::once())->method('supports')->with($collectionMock)->willReturn(true);
        $this->renderer2Mock->expects(self::once())->method('render')->with($collectionMock, null)->willReturn('rendered content');
        self::assertSame('rendered content', $this->collectionRendererStrategy->render($collectionMock));
    }

    public function testRendersCollectionWithCountToRender(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $this->renderer1Mock->expects(self::once())->method('supports')->with($collectionMock)->willReturn(false);
        $this->renderer2Mock->expects(self::once())->method('supports')->with($collectionMock)->willReturn(true);
        $this->renderer2Mock->expects(self::once())->method('render')->with($collectionMock, 5)->willReturn('rendered content with count');
        self::assertSame('rendered content with count', $this->collectionRendererStrategy->render($collectionMock, 5));
    }

    public function testReturnsEmptyStringWhenNoRendererSupportsCollection(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $this->renderer1Mock->expects(self::once())->method('supports')->with($collectionMock)->willReturn(false);
        $this->renderer2Mock->expects(self::once())->method('supports')->with($collectionMock)->willReturn(false);
        self::assertSame('', $this->collectionRendererStrategy->render($collectionMock));
    }
}
