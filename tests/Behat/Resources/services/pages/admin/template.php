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

use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Template\CreatePage;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Template\IndexPage;
use Tests\Sylius\CmsPlugin\Behat\Page\Admin\Template\UpdatePage;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.page.admin.template.index', IndexPage::class)
        ->private()
        ->parent('sylius.behat.page.admin.crud.index')
        ->args(['sylius_cms_admin_template_index']);

    $services->set('sylius_cms.behat.page.admin.template.create', CreatePage::class)
        ->private()
        ->parent('sylius.behat.page.admin.crud.create')
        ->args(['sylius_cms_admin_template_create']);

    $services->set('sylius_cms.behat.page.admin.template.update', UpdatePage::class)
        ->private()
        ->parent('sylius.behat.page.admin.crud.update')
        ->args(['sylius_cms_admin_template_update']);
};
