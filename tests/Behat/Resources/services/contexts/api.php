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

use Sylius\Behat\Client\ResponseCheckerInterface;
use Tests\Sylius\CmsPlugin\Behat\Context\Api\BlockContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Api\CollectionContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Api\MediaContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Api\PageContext;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.context.api.block', BlockContext::class)
        ->args([
            service('sylius_cms.behat.api_platform_client.shop.block'),
            service(ResponseCheckerInterface::class),
        ]);

    $services->set('sylius_cms.behat.context.api.media', MediaContext::class)
        ->args([
            service('sylius_cms.behat.api_platform_client.shop.media'),
            service(ResponseCheckerInterface::class),
        ]);

    $services->set('sylius_cms.behat.context.api.page', PageContext::class)
        ->args([
            service('sylius_cms.behat.api_platform_client.shop.page'),
            service(ResponseCheckerInterface::class),
        ]);

    $services->set('sylius_cms.behat.context.api.collection', CollectionContext::class)
        ->args([
            service('sylius_cms.behat.api_platform_client.shop.collection'),
            service(ResponseCheckerInterface::class),
        ]);
};
