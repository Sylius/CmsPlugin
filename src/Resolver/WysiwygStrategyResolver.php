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

namespace Sylius\CmsPlugin\Resolver;

use Sylius\CmsPlugin\Form\Strategy\Wysiwyg\WysiwygStrategyInterface;

final class WysiwygStrategyResolver implements WysiwygStrategyResolverInterface
{
    public function __construct(
        private array $strategies,
        private string $default,
    ) {
    }

    public function getStrategy(string $wysiwygType): WysiwygStrategyInterface
    {
        return $this->strategies[$wysiwygType] ?? $this->strategies[$this->default];
    }
}
