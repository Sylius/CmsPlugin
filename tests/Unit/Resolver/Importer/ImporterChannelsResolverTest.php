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

namespace Tests\Sylius\CmsPlugin\Unit\Resolver\Importer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Assigner\ChannelsAssignerInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterChannelsResolver;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;

final class ImporterChannelsResolverTest extends TestCase
{
    /** @var ChannelsAssignerInterface&MockObject */
    private MockObject $channelsAssignerMock;

    private ImporterChannelsResolver $importerChannelsResolver;

    protected function setUp(): void
    {
        $this->channelsAssignerMock = $this->createMock(ChannelsAssignerInterface::class);
        $this->importerChannelsResolver = new ImporterChannelsResolver($this->channelsAssignerMock);
    }

    public function testResolvesChannelsForChannelsAware(): void
    {
        /** @var ChannelsAwareInterface&MockObject $channelsAwareMock */
        $channelsAwareMock = $this->createMock(ChannelsAwareInterface::class);
        $channelsRow = 'channel1, channel2, channel3';
        $channelsCodes = ['channel1', 'channel2', 'channel3'];
        $this->channelsAssignerMock->expects(self::once())->method('assign')->with($channelsAwareMock, $channelsCodes);
        $this->importerChannelsResolver->resolve($channelsAwareMock, $channelsRow);
    }

    public function testSkipsResolutionWhenChannelsRowIsNull(): void
    {
        /** @var ChannelsAwareInterface&MockObject $channelsAwareMock */
        $channelsAwareMock = $this->createMock(ChannelsAwareInterface::class);
        $channelsRow = null;
        $this->channelsAssignerMock->expects(self::never())->method('assign');
        $this->importerChannelsResolver->resolve($channelsAwareMock, $channelsRow);
    }
}
