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

namespace Tests\Sylius\CmsPlugin\Unit\Fixture\Assigner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Fixture\Assigner\ChannelsAssigner;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class ChannelsAssignerTest extends TestCase
{
    /** @var MockObject&ChannelRepositoryInterface<ChannelInterface> */
    private MockObject $channelRepository;

    private ChannelsAssigner $channelsAssigner;

    protected function setUp(): void
    {
        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);

        $this->channelsAssigner = new ChannelsAssigner($this->channelRepository);
    }

    public function testImplementsChannelsAssignerInterface(): void
    {
        self::assertInstanceOf(ChannelsAssigner::class, $this->channelsAssigner);
    }

    public function testAssignsChannels(): void
    {
        /** @var ChannelsAwareInterface&MockObject $channelsAware */
        $channelsAware = $this->createMock(ChannelsAwareInterface::class);
        /** @var ChannelInterface&MockObject $webChannel */
        $webChannel = $this->createMock(ChannelInterface::class);
        /** @var ChannelInterface&MockObject $posChannel */
        $posChannel = $this->createMock(ChannelInterface::class);

        $this->channelRepository
            ->expects(self::once())
            ->method('findBy')
            ->with(['code' => ['web', 'pos']])
            ->willReturn([$webChannel, $posChannel])
        ;

        $channelsAware->expects(self::exactly(2))->method('addChannel');

        $this->channelsAssigner->assign($channelsAware, ['web', 'pos']);
    }
}
