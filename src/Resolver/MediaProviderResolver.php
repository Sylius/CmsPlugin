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

use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Webmozart\Assert\Assert;

final class MediaProviderResolver implements MediaProviderResolverInterface
{
    public function __construct(private ServiceRegistryInterface $providerRegistry)
    {
    }

    public function resolveProvider(MediaInterface $media): ProviderInterface
    {
        Assert::notNull($media->getType());
        /** @var ProviderInterface $provider */
        $provider = $this->providerRegistry->get($media->getType());

        return $provider;
    }
}
