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
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Renderer\PageLinkRenderer;
use Sylius\CmsPlugin\Renderer\PageLinkRendererInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class PageLinkRendererTest extends TestCase
{
    /** @var UrlGeneratorInterface&MockObject */
    private MockObject $urlGeneratorMock;

    /** @var Environment&MockObject */
    private MockObject $twigMock;

    private PageLinkRenderer $pageLinkRenderer;

    protected function setUp(): void
    {
        $this->urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $this->twigMock = $this->createMock(Environment::class);
        $this->pageLinkRenderer = new PageLinkRenderer($this->urlGeneratorMock, $this->twigMock);
    }

    public function testImplementsPageLinkRendererInterface(): void
    {
        self::assertInstanceOf(PageLinkRendererInterface::class, $this->pageLinkRenderer);
    }

    public function testRendersPageLinkWithDefaultTemplate(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $pageMock->expects(self::once())->method('getSlug')->willReturn('page-slug');
        $pageMock->expects(self::once())->method('getName')->willReturn('Page Name');
        $this->urlGeneratorMock->expects(self::once())->method('generate')->with('sylius_cms_shop_page_show', ['slug' => 'page-slug'], UrlGeneratorInterface::ABSOLUTE_URL)->willReturn('http://example.com/page-slug');
        $this->twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/page/show/link.html.twig', [
            'link' => 'http://example.com/page-slug',
            'name' => 'Page Name',
        ])->willReturn('<a href="http://example.com/page-slug">Page Name</a>');
        self::assertSame('<a href="http://example.com/page-slug">Page Name</a>', $this->pageLinkRenderer->render($pageMock));
    }

    public function testRendersPageLinkWithCustomTemplate(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $pageMock->expects(self::once())->method('getSlug')->willReturn('page-slug');
        $pageMock->expects(self::once())->method('getName')->willReturn('Page Name');
        $this->urlGeneratorMock->expects(self::once())->method('generate')->with('sylius_cms_shop_page_show', ['slug' => 'page-slug'], UrlGeneratorInterface::ABSOLUTE_URL)->willReturn('http://example.com/page-slug');
        $this->twigMock->expects(self::once())->method('render')->with('custom_template.html.twig', [
            'link' => 'http://example.com/page-slug',
            'name' => 'Page Name',
        ])->willReturn('<a href="http://example.com/page-slug">Page Name</a>');
        self::assertSame('<a href="http://example.com/page-slug">Page Name</a>', $this->pageLinkRenderer->render($pageMock, 'custom_template.html.twig'));
    }
}
