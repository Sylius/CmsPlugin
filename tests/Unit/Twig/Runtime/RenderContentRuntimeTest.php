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
use Sylius\CmsPlugin\Entity\ContentableInterface;
use Sylius\CmsPlugin\Twig\Parser\ContentParserInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderContentRuntime;

final class RenderContentRuntimeTest extends TestCase
{
    /** @var ContentParserInterface&MockObject */
    private MockObject $contentParserMock;

    private RenderContentRuntime $renderContentRuntime;

    protected function setUp(): void
    {
        $this->contentParserMock = $this->createMock(ContentParserInterface::class);
        $this->renderContentRuntime = new RenderContentRuntime($this->contentParserMock);
    }

    public function testRendersContent(): void
    {
        /** @var ContentableInterface&MockObject $contentableResourceMock */
        $contentableResourceMock = $this->createMock(ContentableInterface::class);
        $this->contentParserMock->expects(self::once())->method('parse')->with('content')->willReturn('content');
        $contentableResourceMock->expects(self::once())->method('getContent')->willReturn('content');
        self::assertSame('content', $this->renderContentRuntime->renderContent($contentableResourceMock));
    }
}
