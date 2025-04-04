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

namespace Sylius\CmsPlugin\Renderer;

use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Renderer\Collection\CollectionRendererInterface;

final class CollectionRendererStrategy implements CollectionRendererStrategyInterface
{
    /**
     * @param CollectionRendererInterface[] $renderers
     */
    public function __construct(private iterable $renderers)
    {
    }

    public function render(CollectionInterface $collection, ?int $countToRender = null): string
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($collection)) {
                return $renderer->render($collection, $countToRender);
            }
        }

        return '';
    }
}
