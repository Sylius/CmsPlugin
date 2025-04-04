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

namespace Sylius\CmsPlugin\MediaProvider;

final class FilenameHelper
{
    private const REPLACE_WITH = '';

    public static function removeSlashes(string $string, string $replaceWith = self::REPLACE_WITH): string
    {
        return str_replace('\\', $replaceWith, str_replace('/', $replaceWith, $string));
    }
}
