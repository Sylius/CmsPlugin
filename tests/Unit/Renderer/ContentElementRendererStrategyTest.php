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

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Renderer\ContentElement\ContentElementRendererInterface;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategy;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;
use Sylius\CmsPlugin\Twig\Parser\ContentParserInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContentElementRendererStrategyTest extends TestCase
{
    /** @var ContentParserInterface&MockObject */
    private MockObject $contentParserMock;

    /** @var LocaleContextInterface&MockObject */
    private MockObject $localeContextMock;

    /** @var ContentElementRendererInterface&MockObject */
    private MockObject $rendererMock;

    private ContentElementRendererStrategy $contentElementRendererStrategy;

    protected function setUp(): void
    {
        $this->contentParserMock = $this->createMock(ContentParserInterface::class);
        $this->localeContextMock = $this->createMock(LocaleContextInterface::class);
        $this->rendererMock = $this->createMock(ContentElementRendererInterface::class);
        $this->contentElementRendererStrategy = new ContentElementRendererStrategy($this->contentParserMock, $this->localeContextMock, [$this->rendererMock]);
    }

    public function testImplementsContentElementRendererStrategyInterface(): void
    {
        self::assertInstanceOf(ContentElementRendererStrategyInterface::class, $this->contentElementRendererStrategy);
    }

    public function testRendersAPageContentElementCorrectly(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        /** @var ContentConfigurationInterface&MockObject $contentElementMock */
        $contentElementMock = $this->createMock(ContentConfigurationInterface::class);

        $pageMock->expects(self::once())->method('getContentElements')->willReturn(new ArrayCollection([$contentElementMock]));
        $this->localeContextMock->expects(self::once())->method('getLocaleCode')->willReturn('en_US');
        $contentElementMock->expects(self::once())->method('getLocale')->willReturn('en_US');

        $this->rendererMock->expects(self::once())->method('supports')->with($contentElementMock)->willReturn(true);
        $this->rendererMock->expects(self::once())->method('render')->with($contentElementMock)->willReturn('&lt;p&gt;Hello World&lt;/p&gt;');
        $this->contentParserMock->expects(self::once())->method('parse')->with('<p>Hello World</p>')->willReturn('<p>Hello World</p>');

        self::assertSame('<p>Hello World</p>', $this->contentElementRendererStrategy->render($pageMock));
    }

    public function testSkipsContentElementWithNonMatchingLocale(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        /** @var ContentConfigurationInterface&MockObject $contentElementMock */
        $contentElementMock = $this->createMock(ContentConfigurationInterface::class);

        $blockMock->expects(self::once())->method('getContentElements')->willReturn(new ArrayCollection([$contentElementMock]));
        $this->localeContextMock->expects(self::once())->method('getLocaleCode')->willReturn('en_US');
        $contentElementMock->expects(self::once())->method('getLocale')->willReturn('fr_FR');

        $this->contentParserMock->expects(self::once())->method('parse')->with('')->willReturn('');

        self::assertSame('', $this->contentElementRendererStrategy->render($blockMock));
    }

    public function testRendersOnlySupportedContentElements(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        /** @var ContentConfigurationInterface&MockObject $supportedElementMock */
        $supportedElementMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var ContentConfigurationInterface&MockObject $unsupportedElementMock */
        $unsupportedElementMock = $this->createMock(ContentConfigurationInterface::class);

        $blockMock->expects(self::once())->method('getContentElements')->willReturn(new ArrayCollection([$supportedElementMock, $unsupportedElementMock]));
        $this->localeContextMock->expects(self::exactly(2))->method('getLocaleCode')->willReturn('en_US');
        $supportedElementMock->expects(self::once())->method('getLocale')->willReturn('en_US');
        $unsupportedElementMock->expects(self::once())->method('getLocale')->willReturn('en_US');

        $this->rendererMock->expects(self::once())->method('render')->with($supportedElementMock)->willReturn('&lt;p&gt;Supported&lt;/p&gt;');
        $this->rendererMock->expects(self::exactly(2))->method('supports')->willReturnOnConsecutiveCalls(true, false);
        $this->contentParserMock->expects(self::once())->method('parse')->with('<p>Supported</p>')->willReturn('<p>Supported</p>');

        self::assertSame('<p>Supported</p>', $this->contentElementRendererStrategy->render($blockMock));
    }
}
