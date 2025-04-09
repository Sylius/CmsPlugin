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

namespace Tests\Sylius\CmsPlugin\Unit\MediaProvider;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\MediaProvider\FilenameHelper;

final class FilenameHelperTest extends TestCase
{
    #[DataProvider('getData')]
    public function testRemovesSlashesFromTheString(
        string $filename,
        false|string $replacement,
        string $expected,
    ): void {
        if (false !== $replacement) {
            self::assertSame($expected, FilenameHelper::removeSlashes($filename, $replacement));
        } else {
            self::assertSame($expected, FilenameHelper::removeSlashes($filename));
        }
    }

    /** @return iterable<string, array{filename: string, replacement: false|string,  expected: string}> */
    public static function getData(): iterable
    {
        yield 'filename with slashes' => [
            'filename' => 'path/to/file',
            'replacement' => '-',
            'expected' => 'path-to-file',
        ];

        yield 'filename without slashes with replacement' => [
            'filename' => 'filename',
            'replacement' => '-',
            'expected' => 'filename',
        ];

        yield 'filename without slashes without replacement' => [
            'filename' => 'filename',
            'replacement' => false,
            'expected' => 'filename',
        ];

        yield 'no replacement' => [
            'filename' => 'path/to/file',
            'replacement' => false,
            'expected' => 'pathtofile',
        ];
    }
}
