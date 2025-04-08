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
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Renderer\Collection\CollectionPagesRenderer;
use Sylius\CmsPlugin\Renderer\Collection\CollectionRendererInterface;
use Sylius\CmsPlugin\Renderer\PageLinkRendererInterface;

final class CollectionPagesRendererTest extends TestCase
{
    /** @var PageLinkRendererInterface&MockObject */
    private MockObject $pageLinkRendererMock;

    private CollectionPagesRenderer $collectionPagesRenderer;

    protected function setUp(): void
    {
        $this->pageLinkRendererMock = $this->createMock(PageLinkRendererInterface::class);
        $this->collectionPagesRenderer = new CollectionPagesRenderer($this->pageLinkRendererMock);
    }

    public function testImplementsCollectionRendererInterface(): void
    {
        self::assertInstanceOf(CollectionRendererInterface::class, $this->collectionPagesRenderer);
    }

    public function testRendersPagesFromCollection(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var PageInterface&MockObject $page1Mock */
        $page1Mock = $this->createMock(PageInterface::class);
        /** @var PageInterface&MockObject $page2Mock */
        $page2Mock = $this->createMock(PageInterface::class);
        $page1Mock->method('getId')->willReturn(2);
        $page2Mock->method('getId')->willReturn(1);

        $collectionMock->method('getPages')->willReturn(new ArrayCollection([$page1Mock, $page2Mock]));
        $this->pageLinkRendererMock->method('render')->willReturnOnConsecutiveCalls('page1_content', 'page2_content');

        self::assertSame('page1_contentpage2_content', $this->collectionPagesRenderer->render($collectionMock));
    }

    public function testLimitsNumberOfRenderedPages(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var PageInterface&MockObject $page1Mock */
        $page1Mock = $this->createMock(PageInterface::class);
        /** @var PageInterface&MockObject $page2Mock */
        $page2Mock = $this->createMock(PageInterface::class);
        $page1Mock->method('getId')->willReturn(2);
        $page2Mock->method('getId')->willReturn(1);

        $collectionMock->method('getPages')->willReturn(new ArrayCollection([$page1Mock, $page2Mock]));
        $this->pageLinkRendererMock->method('render')->willReturnOnConsecutiveCalls('page1_content', 'page2_content');

        self::assertSame('page1_content', $this->collectionPagesRenderer->render($collectionMock, 1));
    }

    public function testSupportsCollectionsWithPages(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);

        $collectionMock->expects(self::once())->method('getPages')->willReturn(new ArrayCollection([$pageMock]));

        self::assertTrue($this->collectionPagesRenderer->supports($collectionMock));
    }

    public function testDoesNotSupportEmptyCollections(): void
    {
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $collectionMock->expects(self::once())->method('getPages')->willReturn(new ArrayCollection());

        self::assertFalse($this->collectionPagesRenderer->supports($collectionMock));
    }
}
