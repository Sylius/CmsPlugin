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

namespace Sylius\CmsPlugin\Renderer;

use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\PageInterface;

interface ContentElementRendererStrategyInterface
{
    public function render(BlockInterface|PageInterface $item): string;
}
