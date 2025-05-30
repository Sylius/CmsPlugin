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

namespace Sylius\CmsPlugin\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;
use Webmozart\Assert\Assert;

final class MediaUploadListener
{
    public function __construct(private MediaProviderResolverInterface $mediaProviderResolver)
    {
    }

    public function uploadMedia(ResourceControllerEvent $event): void
    {
        /** @var MediaInterface|null $media */
        $media = $event->getSubject();

        Assert::isInstanceOf($media, MediaInterface::class);

        $this->mediaProviderResolver->resolveProvider($media)->upload($media);
    }
}
