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
use Behat\Config\Filter\TagFilter;
use Behat\Config\Profile;
use Behat\Config\Suite;

return (new Config())
    ->withProfile(
        (new Profile('default'))
        ->withSuite(
            (new Suite('ui_managing_media'))
            ->withContexts(
                'sylius.behat.context.hook.doctrine_orm',
            )
            ->withContexts(
                'sylius.behat.context.setup.channel',
                'sylius.behat.context.setup.admin_security',
                'sylius.behat.context.setup.product',
                'sylius_cms.behat.context.setup.media',
                'sylius_cms.behat.context.setup.collection',
            )
            ->withContexts(
                'sylius_cms.behat.context.ui.admin.media',
                'sylius_cms.behat.context.ui.admin.trix_wysiwyg',
                'sylius_cms.behat.context.ui.admin.quill_wysiwyg',
                'sylius_cms.behat.context.ui.admin.media',
            )
            ->withFilter(new TagFilter('@managing_media&&@ui')),
        ),
    )
;
