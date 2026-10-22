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

use Sylius\CmsPlugin\Resolver\BlockResourceResolver;
use Sylius\CmsPlugin\Resolver\CollectionResourceResolver;
use Sylius\CmsPlugin\Resolver\MediaProviderResolver;
use Sylius\CmsPlugin\Resolver\MediaResourceResolver;
use Sylius\CmsPlugin\Resolver\PageResourceResolver;
use Sylius\CmsPlugin\Resolver\ResourceResolver;
use Sylius\CmsPlugin\Resolver\WysiwygStrategyResolver;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('sylius_cms.resolver.resource.page', ResourceResolver::class)
        ->args([
            service('sylius_cms.repository.page'),
            service('sylius_cms.factory.page'),
            'code',
        ]);

    $services->set('sylius_cms.resolver.resource.collection', ResourceResolver::class)
        ->args([
            service('sylius_cms.repository.collection'),
            service('sylius_cms.factory.collection'),
            'code',
        ]);

    $services->set('sylius_cms.resolver.resource.block', ResourceResolver::class)
        ->args([
            service('sylius_cms.repository.block'),
            service('sylius_cms.factory.block'),
            'code',
        ]);

    $services->set('sylius_cms.resolver.resource.media', ResourceResolver::class)
        ->args([
            service('sylius_cms.repository.media'),
            service('sylius_cms.factory.media'),
            'code',
        ]);

    $services->set('sylius_cms.resolver.block_resource', BlockResourceResolver::class)
        ->args([
            service('sylius_cms.repository.block'),
            service('logger'),
            service('sylius.context.channel'),
        ]);

    $services->set('sylius_cms.resolver.collection_resource', CollectionResourceResolver::class)
        ->args([
            service('sylius_cms.repository.collection'),
            service('logger'),
        ]);

    $services->set('sylius_cms.resolver.page_resource', PageResourceResolver::class)
        ->args([
            service('sylius_cms.repository.page'),
            service('logger'),
        ]);

    $services->set('sylius_cms.resolver.media_resource', MediaResourceResolver::class)
        ->args([
            service('sylius_cms.repository.media'),
            service('sylius.context.channel'),
            service('logger'),
        ]);

    $services->set('sylius_cms.resolver.media_provider', MediaProviderResolver::class)
        ->args([service('sylius_cms.registry.media_provider')]);

    $services->set('sylius_cms.resolver.wysiwyg_strategy_resolver', WysiwygStrategyResolver::class)
        ->args([
            tagged_iterator('sylius_cms.wysiwyg_strategy', indexAttribute: 'strategy'),
            '%sylius_cms.wysiwyg_editor%',
        ]);
};
