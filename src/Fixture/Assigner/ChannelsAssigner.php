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

namespace Sylius\CmsPlugin\Fixture\Assigner;

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class ChannelsAssigner implements ChannelsAssignerInterface
{
    /** @param ChannelRepositoryInterface<ChannelInterface> $channelRepository */
    public function __construct(private ChannelRepositoryInterface $channelRepository)
    {
    }

    public function assign(ChannelsAwareInterface $channelsAware, array $channelsCodes): void
    {
        $channels = $this->channelRepository->findBy(['code' => $channelsCodes]);

        foreach ($channels as $channel) {
            $channelsAware->addChannel($channel);
        }
    }
}
