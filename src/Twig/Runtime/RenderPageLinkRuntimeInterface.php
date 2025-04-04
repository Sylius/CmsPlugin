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

use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

interface RenderPageLinkRuntimeInterface extends RuntimeExtensionInterface
{
    public function renderLinkForCode(
        Environment $environment,
        string $code,
        array $options = [],
        ?string $template = null,
    ): string;

    public function getLinkForCode(string $code, array $options = []): string;
}
