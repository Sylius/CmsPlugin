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

namespace Sylius\CmsPlugin\Uploader;

use Sylius\CmsPlugin\Entity\MediaInterface;

interface MediaUploaderInterface
{
    public function upload(MediaInterface $media): void;

    public function remove(string $path): bool;
}
