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
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderPageLinkRuntime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class RenderPageLinkRuntimeTest extends TestCase
{
    /** @var PageRepositoryInterface&MockObject */
    private MockObject $pageRepositoryMock;

    /** @var RouterInterface&MockObject */
    private MockObject $routerMock;

    private RenderPageLinkRuntime $renderPageLinkRuntime;

    protected function setUp(): void
    {
        $this->pageRepositoryMock = $this->createMock(PageRepositoryInterface::class);
        $this->routerMock = $this->createMock(RouterInterface::class);

        $this->renderPageLinkRuntime = new RenderPageLinkRuntime($this->pageRepositoryMock, $this->routerMock, 'defaultTemplate');
    }

    public function testRendersLinkForCode(): void
    {
        /** @var Environment&MockObject $environmentMock */
        $environmentMock = $this->createMock(Environment::class);
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $options = [];
        $code = 'CODE';

        $this->pageRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with($code)->willReturn($pageMock);
        $environmentMock->expects(self::once())->method('render')->with('defaultTemplate', [
            'page' => $pageMock,
            'options' => $options,
        ])->willReturn('link');

        self::assertSame('link', $this->renderPageLinkRuntime->renderLinkForCode($environmentMock, $code, $options));
    }

    public function testGetsLinkForCode(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $code = 'CODE';
        $slug = 'SLUG';

        $this->pageRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with($code)->willReturn($pageMock);
        $pageMock->expects(self::once())->method('getSlug')->willReturn($slug);
        $this->routerMock->expects(self::once())->method('generate')->with('sylius_cms_shop_page_show', ['slug' => $slug])->willReturn('link');

        self::assertSame('link', $this->renderPageLinkRuntime->getLinkForCode($code));
    }

    public function testReturnsNotFoundMessageWhenGettingLinkForCode(): void
    {
        $this->pageRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with('CODE')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->renderPageLinkRuntime->getLinkForCode('CODE');
    }
}
