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

namespace spec\Sylius\CmsPlugin\Resolver;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\CmsPlugin\Resolver\BlockResourceResolver;
use Sylius\CmsPlugin\Resolver\BlockResourceResolverInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class BlockResourceResolverSpec extends ObjectBehavior
{
    public function let(
        BlockRepositoryInterface $blockRepository,
        LoggerInterface $logger,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        RepositoryInterface $localeRepository,
    ) {
        $this->beConstructedWith($blockRepository, $logger, $channelContext, $localeContext, $localeRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BlockResourceResolver::class);
    }

    public function it_implements_block_resource_resolver_interface(): void
    {
        $this->shouldHaveType(BlockResourceResolverInterface::class);
    }

    public function it_returns_block_if_found_in_database(
        BlockRepositoryInterface $blockRepository,
        BlockInterface $block,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
    ) {
        $channel->getCode()->willReturn('WEB');
        $channelContext->getChannel()->willReturn($channel);
        $blockRepository->findEnabledByCode('homepage_banner', 'WEB')->willReturn($block);

        $this->findOrLog('homepage_banner')->shouldReturn($block);
    }
}
