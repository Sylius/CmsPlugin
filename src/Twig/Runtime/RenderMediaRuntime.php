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

namespace Sylius\CmsPlugin\Twig\Runtime;

use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;
use Sylius\CmsPlugin\Resolver\MediaResourceResolverInterface;

final class RenderMediaRuntime implements RenderMediaRuntimeInterface
{
    public function __construct(
        private MediaProviderResolverInterface $mediaProviderResolver,
        private MediaResourceResolverInterface $mediaResourceResolver,
    ) {
    }

    public function renderMedia(string $code, ?string $template = null): string
    {
        $media = $this->mediaResourceResolver->findOrLog($code);

        if (null !== $media) {
            return $this->mediaProviderResolver->resolveProvider($media)->render($media, $template);
        }

        return '';
    }
}
