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

use Sylius\Behat\Client\ApiPlatformClient;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.api_platform_client.shop.block', ApiPlatformClient::class)
        ->parent('sylius.behat.api_platform_client')
        ->args(['shop/cms-plugin']);

    $services->set('sylius_cms.behat.api_platform_client.shop.media', ApiPlatformClient::class)
        ->parent('sylius.behat.api_platform_client')
        ->args(['shop/cms-plugin']);

    $services->set('sylius_cms.behat.api_platform_client.shop.page', ApiPlatformClient::class)
        ->parent('sylius.behat.api_platform_client')
        ->args(['shop/cms-plugin']);

    $services->set('sylius_cms.behat.api_platform_client.shop.collection', ApiPlatformClient::class)
        ->parent('sylius.behat.api_platform_client')
        ->args(['shop/cms-plugin']);
};
