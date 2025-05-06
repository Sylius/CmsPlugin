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

use League\Flysystem\FilesystemOperator;

final class ResolveMediaVideoPathRuntime implements ResolveMediaVideoPathRuntimeInterface
{
    private string $baseUrl;

    public function __construct(
        private readonly FilesystemOperator $videoStorage,
        string $videosDir,
        string $publicDir,
    ) {
        $this->baseUrl = str_replace($publicDir, '', $videosDir);
    }

    public function resolve(string $path): string
    {
        if (!$this->videoStorage->has($path)) {
            return $path;
        }

        return sprintf(
            '%s%s%s',
            rtrim($this->baseUrl, \DIRECTORY_SEPARATOR),
            \DIRECTORY_SEPARATOR,
            ltrim($path, \DIRECTORY_SEPARATOR),
        );
    }
}
