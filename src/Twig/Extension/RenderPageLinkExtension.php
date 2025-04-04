<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Twig\Extension;

use Sylius\CmsPlugin\Twig\Runtime\RenderPageLinkRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RenderPageLinkExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_cms_render_page_link', [RenderPageLinkRuntime::class, 'renderLinkForCode'], [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]),
            new TwigFunction('sylius_cms_get_page_url', [RenderPageLinkRuntime::class, 'getLinkForCode']),
        ];
    }
}
