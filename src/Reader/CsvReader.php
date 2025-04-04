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

namespace Sylius\CmsPlugin\Reader;

use League\Csv\Reader;

final class CsvReader implements ReaderInterface
{
    public function read(string $filePath): \Iterator
    {
        return Reader::createFromPath($filePath)->setHeaderOffset(0)->getIterator();
    }
}
