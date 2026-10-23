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

use Sylius\CmsPlugin\MediaProvider\GenericProvider;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.media_provider.image', GenericProvider::class)
        ->args([
            service('sylius_cms.media_uploader.image'),
            service('twig'),
            '@SyliusCmsPlugin/shop/media/show/image.html.twig',
        ])
        ->tag('sylius_cms.media_provider', ['type' => 'image', 'label' => 'sylius_cms.ui.image_provider']);

    $services->set('sylius_cms.media_provider.video', GenericProvider::class)
        ->args([
            service('sylius_cms.media_uploader.video'),
            service('twig'),
            '@SyliusCmsPlugin/shop/media/show/video.html.twig',
        ])
        ->tag('sylius_cms.media_provider', ['type' => 'video', 'label' => 'sylius_cms.ui.video_provider']);

    $services->set('sylius_cms.media_provider.file', GenericProvider::class)
        ->args([
            service('sylius_cms.media_uploader.file'),
            service('twig'),
            '@SyliusCmsPlugin/shop/media/show/file.html.twig',
        ])
        ->tag('sylius_cms.media_provider', ['type' => 'file', 'label' => 'sylius_cms.ui.file_provider']);
};
