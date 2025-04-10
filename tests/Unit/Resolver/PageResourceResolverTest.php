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

namespace Tests\Sylius\CmsPlugin\Unit\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Repository\PageRepositoryInterface;
use Sylius\CmsPlugin\Resolver\PageResourceResolver;

final class PageResourceResolverTest extends TestCase
{
    /** @var PageRepositoryInterface&MockObject */
    private MockObject $pageRepositoryMock;

    /** @var LoggerInterface&MockObject */
    private MockObject $loggerMock;

    private PageResourceResolver $pageResourceResolver;

    protected function setUp(): void
    {
        $this->pageRepositoryMock = $this->createMock(PageRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->pageResourceResolver = new PageResourceResolver($this->pageRepositoryMock, $this->loggerMock);
    }

    public function testLogsWarningIfPageWasNotFound(): void
    {
        $this->pageRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with('homepage_banner')->willReturn(null);
        $this->loggerMock->expects(self::once())->method('warning')->with(sprintf(
            'Page with "%s" code was not found in the database.',
            'homepage_banner',
        ))
        ;
        $this->pageResourceResolver->findOrLog('homepage_banner');
    }

    public function testReturnsPageIfFoundInDatabase(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $this->pageRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with('homepage_banner')->willReturn($pageMock);
        self::assertSame($pageMock, $this->pageResourceResolver->findOrLog('homepage_banner'));
    }
}
