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

namespace Tests\Sylius\CmsPlugin\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Exception\ImportFailedException;

final class ImportFailedExceptionTest extends TestCase
{
    public function testHasCustomMessage(): void
    {
        self::assertSame(
            'Import failed at index 1. Exception message: not blank',
            (new ImportFailedException('not blank', 1))->getMessage(),
        );
    }
}
