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

namespace Sylius\CmsPlugin\Resolver;

use Psr\Log\LoggerInterface;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Webmozart\Assert\Assert;

final class BlockResourceResolver implements BlockResourceResolverInterface
{
    public function __construct(
        private BlockRepositoryInterface $blockRepository,
        private LoggerInterface $logger,
        private ChannelContextInterface $channelContext,
    ) {
    }

    public function findOrLog(string $code): ?BlockInterface
    {
        $channelCode = $this->channelContext->getChannel()->getCode();
        Assert::notNull($channelCode);

        $block = $this->blockRepository->findEnabledByCode($code, $channelCode);
        if (false === $block instanceof BlockInterface) {
            $this->logger->warning(sprintf(
                'Block with "%s" code was not found in the database.',
                $code,
            ));

            return null;
        }

        return $block;
    }
}
