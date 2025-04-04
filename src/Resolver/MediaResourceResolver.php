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
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Webmozart\Assert\Assert;

final class MediaResourceResolver implements MediaResourceResolverInterface
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepository,
        private ChannelContextInterface $channelContext,
        private LoggerInterface $logger,
    ) {
    }

    public function findOrLog(string $code): ?MediaInterface
    {
        Assert::notNull($this->channelContext->getChannel()->getCode());
        $media = $this->mediaRepository->findOneEnabledByCode(
            $code,
            $this->channelContext->getChannel()->getCode(),
        );

        if (false === $media instanceof MediaInterface) {
            $this->logger->warning(sprintf(
                'Media with "%s" code was not found in the database.',
                $code,
            ));

            return null;
        }

        return $media;
    }
}
