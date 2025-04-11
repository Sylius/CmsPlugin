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

namespace Tests\Sylius\CmsPlugin\Unit\Twig\Parser;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Twig\Parser\ContentParser;
use Sylius\CmsPlugin\Twig\Runtime\RenderBlockRuntimeInterface;
use Twig\Environment;
use Twig\TwigFunction;

final class ContentParserTest extends TestCase
{
    /** @var MockObject&Environment */
    private MockObject $twigEnvironment;

    /** @var MockObject&RenderBlockRuntimeInterface */
    private MockObject $renderBlockRuntime;

    private ContentParser $contentParser;

    protected function setUp(): void
    {
        $this->twigEnvironment = $this->createMock(Environment::class);
        $this->renderBlockRuntime = $this->createMock(RenderBlockRuntimeInterface::class);

        $this->twigEnvironment
            ->method('getFunctions')
            ->willReturn([
                'sylius_cms_render_block' => new TwigFunction('sylius_cms_render_block', [$this->renderBlockRuntime, 'renderBlock']),
            ])
        ;

        $this->contentParser = new ContentParser($this->twigEnvironment, ['sylius_cms_render_block']);
    }

    public function testImplementsContentParserInterface(): void
    {
        self::assertInstanceOf(ContentParser::class, $this->contentParser);
    }

    public function testParsesStringFunction(): void
    {
        $input = "Let's render! {{ sylius_cms_render_block('intro', '@SyliusCmsPlugin/shop/block/show.html.twig') }}";

        $this->renderBlockRuntime
            ->expects(self::once())
            ->method('renderBlock')
            ->with('intro', '@SyliusCmsPlugin/shop/block/show.html.twig')
            ->willReturn('parsed')
        ;

        self::assertSame("Let's render! parsed", $this->contentParser->parse($input));
    }

    public function testParsesStringFunctions(): void
    {
        $input = <<<TEXT
Let's render! {{ sylius_cms_render_block('intro', '@SyliusCmsPlugin/shop/block/show.html.twig') }}
Let's render twice! {{ sylius_cms_render_block('intro1', '@SyliusCmsPlugin/shop/block/show.html.twig') }}
TEXT;

        $this->renderBlockRuntime
            ->expects(self::exactly(2))
            ->method('renderBlock')
            ->willReturnOnConsecutiveCalls(
                'parsed',
                'parsed2',
            )
        ;

        self::assertSame("Let's render! parsed\nLet's render twice! parsed2", $this->contentParser->parse($input));
    }
}
