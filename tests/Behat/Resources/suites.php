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

use Behat\Config\Config;

return (new Config())
    ->import([
        'suites/ui/managing_blocks.php',
        'suites/ui/managing_pages.php',
        'suites/ui/managing_collections.php',
        'suites/ui/managing_media.php',
        'suites/ui/managing_content_templates.php',
        'suites/ui/shop_blocks.php',
        'suites/ui/shop_media.php',
        'suites/ui/shop_pages.php',

        'suites/api/shop_pages.php',
        'suites/api/shop_blocks.php',
        'suites/api/shop_collections.php',
        'suites/api/shop_media.php',
    ])
;
