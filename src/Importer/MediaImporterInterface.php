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

namespace Sylius\CmsPlugin\Importer;

interface MediaImporterInterface extends ImporterInterface
{
    public const CODE_COLUMN = 'code';

    public const TYPE_COLUMN = 'type';

    public const COLLECTIONS_COLUMN = 'collections';

    public const NAME_COLUMN = 'name__locale__';

    public const CONTENT_COLUMN = 'content__locale__';

    public const ALT_COLUMN = 'alt__locale__';
}
