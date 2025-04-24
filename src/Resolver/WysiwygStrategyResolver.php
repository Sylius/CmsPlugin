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

use Sylius\CmsPlugin\Form\Strategy\Wysiwyg\WysiwygStrategyInterface;

final class WysiwygStrategyResolver implements WysiwygStrategyResolverInterface
{
    /** @param array<string, WysiwygStrategyInterface> $strategies */
    public function __construct(
        private iterable $strategies,
        private string $default,
    ) {
    }

    public function getStrategy(string $wysiwygType): WysiwygStrategyInterface
    {
        $strategies = is_array($this->strategies) ? $this->strategies : iterator_to_array($this->strategies);

        return $strategies[$wysiwygType] ?? $strategies[$this->default];
    }
}
