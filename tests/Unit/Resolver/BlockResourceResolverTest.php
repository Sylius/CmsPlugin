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
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\CmsPlugin\Resolver\BlockResourceResolver;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class BlockResourceResolverTest extends TestCase
{
    /** @var BlockRepositoryInterface&MockObject */
    private MockObject $blockRepositoryMock;

    /** @var LoggerInterface&MockObject */
    private MockObject $loggerMock;

    /** @var ChannelContextInterface&MockObject */
    private MockObject $channelContextMock;

    private BlockResourceResolver $blockResourceResolver;

    protected function setUp(): void
    {
        $this->blockRepositoryMock = $this->createMock(BlockRepositoryInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->channelContextMock = $this->createMock(ChannelContextInterface::class);

        $this->blockResourceResolver = new BlockResourceResolver(
            $this->blockRepositoryMock,
            $this->loggerMock,
            $this->channelContextMock,
        );
    }

    public function testReturnsBlockIfFoundInDatabase(): void
    {
        /** @var BlockInterface&MockObject $blockMock */
        $blockMock = $this->createMock(BlockInterface::class);
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        $channelMock->expects(self::once())->method('getCode')->willReturn('WEB');

        $this->channelContextMock->expects(self::once())->method('getChannel')->willReturn($channelMock);
        $this->blockRepositoryMock->expects(self::once())->method('findEnabledByCode')->with('homepage_banner', 'WEB')->willReturn($blockMock);

        self::assertSame($blockMock, $this->blockResourceResolver->findOrLog('homepage_banner'));
    }
}
