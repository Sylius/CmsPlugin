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
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;
use Sylius\CmsPlugin\Resolver\BlockResourceResolverInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderBlockRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderBlockRuntimeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Twig\Environment;

final class RenderBlockRuntimeTest extends TestCase
{
    /** @var BlockResourceResolverInterface&MockObject */
    private MockObject $blockResourceResolverMock;

    /** @var Environment&MockObject */
    private MockObject $templatingEngineMock;

    /** @var ContentElementRendererStrategyInterface&MockObject */
    private MockObject $contentElementRendererStrategyMock;

    private RenderBlockRuntime $renderBlockRuntime;

    protected function setUp(): void
    {
        $this->blockResourceResolverMock = $this->createMock(BlockResourceResolverInterface::class);
        $this->templatingEngineMock = $this->createMock(Environment::class);
        $this->contentElementRendererStrategyMock = $this->createMock(ContentElementRendererStrategyInterface::class);
        $this->renderBlockRuntime = new RenderBlockRuntime($this->blockResourceResolverMock, $this->templatingEngineMock, $this->contentElementRendererStrategyMock);
    }

    public function testImplementsRenderBlockRuntimeInterface(): void
    {
        self::assertInstanceOf(RenderBlockRuntimeInterface::class, $this->renderBlockRuntime);
    }

    public function testReturnsEmptyStringWhenBlockNotFound(): void
    {
        $this->blockResourceResolverMock->expects(self::once())->method('findOrLog')->with('code')->willReturn(null);
        self::assertSame('', $this->renderBlockRuntime->renderBlock('code'));
    }

    public function testReturnsEmptyStringWhenBlockNotDisplayableForTaxon(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        /** @var TaxonInterface&MockObject $taxonMock */
        $taxonMock = $this->createMock(TaxonInterface::class);
        $this->blockResourceResolverMock->expects(self::once())->method('findOrLog')->with('code')->willReturn($blockMock);
        $blockMock->expects(self::once())->method('canBeDisplayedForTaxon')->with($taxonMock)->willReturn(false);
        self::assertSame('', $this->renderBlockRuntime->renderBlock('code', null, $taxonMock));
    }

    public function testReturnsEmptyStringWhenBlockNotDisplayableForProduct(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        /** @var ProductInterface&MockObject $productMock */
        $productMock = $this->createMock(ProductInterface::class);
        $this->blockResourceResolverMock->expects(self::once())->method('findOrLog')->with('code')->willReturn($blockMock);
        $blockMock->expects(self::once())->method('canBeDisplayedForProduct')->with($productMock)->willReturn(false);
        $blockMock->expects(self::once())->method('canBeDisplayedForProductInTaxon')->with($productMock)->willReturn(false);
        self::assertSame('', $this->renderBlockRuntime->renderBlock('code', null, $productMock));
    }

    public function testRendersBlockWithDefaultTemplate(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        $this->blockResourceResolverMock->expects(self::once())->method('findOrLog')->with('code')->willReturn($blockMock);
        $this->contentElementRendererStrategyMock->expects(self::once())->method('render')->with($blockMock)->willReturn('rendered content');
        $this->templatingEngineMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/block/show.html.twig', [
            'content' => 'rendered content',
            'context' => null,
        ])->willReturn('rendered block');
        self::assertSame('rendered block', $this->renderBlockRuntime->renderBlock('code'));
    }

    public function testRendersBlockWithCustomTemplate(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        $this->blockResourceResolverMock->expects(self::once())->method('findOrLog')->with('code')->willReturn($blockMock);
        $this->contentElementRendererStrategyMock->expects(self::once())->method('render')->with($blockMock)->willReturn('rendered content');
        $this->templatingEngineMock->expects(self::once())->method('render')->with('custom_template.html.twig', [
            'content' => 'rendered content',
            'context' => null,
        ])->willReturn('rendered block');
        self::assertSame('rendered block', $this->renderBlockRuntime->renderBlock('code', 'custom_template.html.twig'));
    }
}
