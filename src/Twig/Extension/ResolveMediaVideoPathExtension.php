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

namespace Sylius\CmsPlugin\Twig\Extension;

use Sylius\CmsPlugin\Twig\Runtime\ResolveMediaVideoPathRuntimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ResolveMediaVideoPathExtension extends AbstractExtension
{
    public function __construct(
        private readonly ResolveMediaVideoPathRuntimeInterface $resolveMediaVideoPathRuntime,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_cms_resolve_media_video_path', [$this->resolveMediaVideoPathRuntime, 'resolve']),
        ];
    }
}
