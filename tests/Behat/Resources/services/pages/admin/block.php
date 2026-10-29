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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Behat\Service\Helper\AutocompleteHelperInterface;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Block\CreatePage;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Block\IndexPage;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Block\UpdatePage;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.page.admin.block.index', IndexPage::class)
        ->private()
        ->parent('sylius.behat.page.admin.crud.index')
        ->args(['sylius_cms_admin_block_index']);

    $services->set('sylius_cms.behat.page.admin.block.create', CreatePage::class)
        ->private()
        ->parent('sylius.behat.page.admin.crud.create')
        ->args([
            'sylius_cms_admin_block_create',
            service(AutocompleteHelperInterface::class),
        ]);

    $services->set('sylius_cms.behat.page.admin.block.update', UpdatePage::class)
        ->private()
        ->parent('sylius.behat.page.admin.crud.update')
        ->args([
            'sylius_cms_admin_block_update',
            service(AutocompleteHelperInterface::class),
        ]);
};
