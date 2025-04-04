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

namespace Sylius\CmsPlugin\Importer;

interface BlockImporterInterface extends ImporterInterface
{
    public const CODE_COLUMN = 'code';

    public const NAME_COLUMN = 'name';

    public const ENABLED_COLUMN = 'enabled';

    public const COLLECTIONS_COLUMN = 'collections';

    public const CHANNELS_COLUMN = 'channels';

    public const PRODUCTS_COLUMN = 'products';

    public const PRODUCTS_IN_TAXONS_COLUMN = 'products_in_taxons';

    public const TAXONS_COLUMN = 'taxons';
}
