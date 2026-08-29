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

use FriendsOfBehat\PageObjectExtension\Page\Page;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $container->import('pages/admin.php');
    $container->import('pages/shop.php');

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.page', Page::class)
        ->args([
            service('behat.mink.default_session'),
            service('behat.mink.parameters'),
        ]);
};
