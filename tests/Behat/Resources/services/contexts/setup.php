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

use Tests\Sylius\CmsPlugin\Behat\Context\Setup\BlockContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Setup\CollectionContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Setup\ContentTemplateContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Setup\MediaContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Setup\PageContext;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.context.setup.block', BlockContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius_cms.behat.random_string_generator'),
            service('sylius_cms.factory.block'),
            service('sylius_cms.repository.block'),
        ]);

    $services->set('sylius_cms.behat.context.setup.page', PageContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius_cms.behat.random_string_generator'),
            service('sylius_cms.factory.page'),
            service('sylius_cms.repository.page'),
            service('doctrine.orm.entity_manager'),
            service('sylius.repository.product'),
            service('sylius_cms.repository.collection'),
            service('sylius_cms.media_provider.image'),
        ]);

    $services->set('sylius_cms.behat.context.setup.collection', CollectionContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius_cms.behat.random_string_generator'),
            service('sylius_cms.factory.collection'),
            service('sylius_cms.repository.collection'),
        ]);

    $services->set('sylius_cms.behat.context.setup.media', MediaContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius_cms.behat.random_string_generator'),
            service('sylius_cms.factory.media'),
            service('sylius_cms.repository.media'),
            service('sylius_cms.resolver.media_provider'),
        ]);

    $services->set('sylius_cms.behat.context.setup.content_template', ContentTemplateContext::class)
        ->args([
            service('sylius_cms.factory.template'),
            service('sylius.behat.shared_storage'),
            service('sylius_cms.repository.template'),
        ]);
};
