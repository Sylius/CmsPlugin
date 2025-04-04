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

namespace Sylius\CmsPlugin\Exception;

final class ImportFailedException extends \RuntimeException
{
    public function __construct(string $message, int $index)
    {
        parent::__construct(sprintf('Import failed at index %d. Exception message: %s', $index, $message));
    }
}
