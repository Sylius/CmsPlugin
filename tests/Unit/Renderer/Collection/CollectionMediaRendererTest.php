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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Renderer\Collection\CollectionMediaRenderer;
use Sylius\CmsPlugin\Renderer\Collection\CollectionRendererInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderMediaRuntimeInterface;

final class CollectionMediaRendererTest extends TestCase
{
    /** @var RenderMediaRuntimeInterface&MockObject */
    private MockObject $renderMediaRuntimeMock;

    private CollectionMediaRenderer $collectionMediaRenderer;

    protected function setUp(): void
    {
        $this->renderMediaRuntimeMock = $this->createMock(RenderMediaRuntimeInterface::class);
        $this->collectionMediaRenderer = new CollectionMediaRenderer($this->renderMediaRuntimeMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(CollectionMediaRenderer::class, $this->collectionMediaRenderer);
        self::assertInstanceOf(CollectionRendererInterface::class, $this->collectionMediaRenderer);
    }

    public function testRendersMediaCollection(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var MediaInterface&MockObject $media1Mock */
        $media1Mock = $this->createMock(MediaInterface::class);
        /** @var MediaInterface&MockObject $media2Mock */
        $media2Mock = $this->createMock(MediaInterface::class);
        $media1Mock->method('getId')->willReturn(1);
        $media2Mock->method('getId')->willReturn(2);
        $media1Mock->method('getCode')->willReturn('media_code_1');
        $media2Mock->method('getCode')->willReturn('media_code_2');

        $collectionMock->expects(self::exactly(2))->method('getMedia')->willReturn(new ArrayCollection([$media1Mock, $media2Mock]));
        $this->renderMediaRuntimeMock->expects(self::exactly(2))->method('renderMedia')->willReturnOnConsecutiveCalls('media1', 'media2');

        self::assertSame('media1media2', $this->collectionMediaRenderer->render($collectionMock));
    }

    public function testRendersLimitedNumberOfMedia(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var MediaInterface&MockObject $media1Mock */
        $media1Mock = $this->createMock(MediaInterface::class);
        /** @var MediaInterface&MockObject $media2Mock */
        $media2Mock = $this->createMock(MediaInterface::class);
        $media1Mock->method('getId')->willReturn(1);
        $media2Mock->method('getId')->willReturn(2);
        $media1Mock->method('getCode')->willReturn('media_code_1');
        $media2Mock->method('getCode')->willReturn('media_code_2');

        $collectionMock->expects(self::exactly(2))->method('getMedia')->willReturn(new ArrayCollection([$media1Mock, $media2Mock]));
        $this->renderMediaRuntimeMock->expects(self::once())->method('renderMedia')->with('media_code_1')->willReturn('media1');

        self::assertSame('media1', $this->collectionMediaRenderer->render($collectionMock, 1));
    }

    public function testSupportsCollectionsWithMedia(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var MediaInterface&MockObject $media1Mock */
        $media1Mock = $this->createMock(MediaInterface::class);

        $collectionMock->expects(self::once())->method('getMedia')->willReturn(new ArrayCollection([$media1Mock]));

        self::assertTrue($this->collectionMediaRenderer->supports($collectionMock));
    }

    public function testDoesNotSupportCollectionsWithoutMedia(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $collectionMock->expects(self::once())->method('getMedia')->willReturn(new ArrayCollection());

        self::assertFalse($this->collectionMediaRenderer->supports($collectionMock));
    }
}
