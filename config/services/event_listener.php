<?php

/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\CmsPlugin\EventListener\MediaUploadListener;
use Sylius\CmsPlugin\Menu\ContentManagementMenuBuilder;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.menu.content_management', ContentManagementMenuBuilder::class)
        ->args([service('sylius_cms.menu.reorder')])
        ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.main', 'method' => 'buildMenu']);

    $services->set('sylius_cms.event_listener.media_upload', MediaUploadListener::class)
        ->args([service('sylius_cms.resolver.media_provider')])
        ->tag('kernel.event_listener', ['event' => 'sylius_cms.media.pre_create', 'method' => 'uploadMedia'])
        ->tag('kernel.event_listener', ['event' => 'sylius_cms.media.pre_update', 'method' => 'uploadMedia']);
};
