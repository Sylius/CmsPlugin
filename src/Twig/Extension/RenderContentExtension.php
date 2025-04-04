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

use Sylius\CmsPlugin\Twig\Runtime\RenderContentRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderContentExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_cms_render_content', [RenderContentRuntime::class, 'renderContent'], ['is_safe' => ['html']]),
        ];
    }
}
