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

namespace Sylius\CmsPlugin\MediaProvider;

use Sylius\CmsPlugin\Entity\MediaInterface;

interface ProviderInterface
{
    public function getTemplate(): string;

    /** @param array<string, mixed> $options */
    public function render(
        MediaInterface $media,
        ?string $template = null,
        array $options = [],
    ): string;

    public function upload(MediaInterface $media): void;
}
