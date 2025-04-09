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

namespace Tests\Sylius\CmsPlugin\Unit\Twig\Runtime;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderContentElementsRuntime;

final class RenderContentElementsRuntimeTest extends TestCase
{
    /** @var ContentElementRendererStrategyInterface&MockObject */
    private MockObject $contentElementRendererStrategyMock;

    private RenderContentElementsRuntime $renderContentElementsRuntime;

    protected function setUp(): void
    {
        $this->contentElementRendererStrategyMock = $this->createMock(ContentElementRendererStrategyInterface::class);
        $this->renderContentElementsRuntime = new RenderContentElementsRuntime($this->contentElementRendererStrategyMock);
    }

    public function testRendersABlock(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        $this->contentElementRendererStrategyMock->expects(self::once())->method('render')->with($blockMock)->willReturn('rendered block content');
        self::assertSame('rendered block content', $this->renderContentElementsRuntime->render($blockMock));
    }

    public function testRendersAPage(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $this->contentElementRendererStrategyMock->expects(self::once())->method('render')->with($pageMock)->willReturn('rendered page content');
        self::assertSame('rendered page content', $this->renderContentElementsRuntime->render($pageMock));
    }
}
