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
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\CmsPlugin\Resolver\MediaResourceResolver;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class MediaResourceResolverTest extends TestCase
{
    /** @var MediaRepositoryInterface&MockObject */
    private MockObject $mediaRepositoryMock;

    /** @var ChannelContextInterface&MockObject */
    private MockObject $channelContextMock;

    /** @var LoggerInterface&MockObject */
    private MockObject $loggerMock;

    private MediaResourceResolver $mediaResourceResolver;

    protected function setUp(): void
    {
        $this->mediaRepositoryMock = $this->createMock(MediaRepositoryInterface::class);
        $this->channelContextMock = $this->createMock(ChannelContextInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->mediaResourceResolver = new MediaResourceResolver($this->mediaRepositoryMock, $this->channelContextMock, $this->loggerMock);
    }

    public function testReturnsMediaWhenFound(): void
    {
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        $code = 'media_code';
        $channelCode = 'ecommerce';
        $this->channelContextMock->expects(self::once())->method('getChannel')->willReturn($channelMock);
        $channelMock->expects(self::once())->method('getCode')->willReturn($channelCode);
        $this->mediaRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with($code, $channelCode)->willReturn($mediaMock);
        self::assertSame($mediaMock, $this->mediaResourceResolver->findOrLog($code));
    }

    public function testLogsWarningAndReturnsNullWhenMediaNotFound(): void
    {
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        $code = 'non_existing_code';
        $channelCode = 'ecommerce';
        $this->channelContextMock->expects(self::once())->method('getChannel')->willReturn($channelMock);
        $channelMock->expects(self::once())->method('getCode')->willReturn($channelCode);
        $this->mediaRepositoryMock->expects(self::once())->method('findOneEnabledByCode')->with($code, $channelCode)->willReturn(null);
        $this->loggerMock->expects(self::once())->method('warning')->with(sprintf('Media with "%s" code was not found in the database.', $code));
        self::assertNull($this->mediaResourceResolver->findOrLog($code));
    }
}
