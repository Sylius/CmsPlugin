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

use Sylius\CmsPlugin\Twig\Runtime\TemplateExistsRuntimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TemplateExistsExtension extends AbstractExtension
{
    public function __construct(
        private readonly TemplateExistsRuntimeInterface $templateExistsRuntime,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('template_exists', [$this->templateExistsRuntime, 'exists']),
        ];
    }
}
