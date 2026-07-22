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

use Sylius\CmsPlugin\Fixture\Assigner\ChannelsAssigner;
use Sylius\CmsPlugin\Fixture\Assigner\CollectionsAssigner;
use Sylius\CmsPlugin\Fixture\Assigner\ProductsAssigner;
use Sylius\CmsPlugin\Fixture\Assigner\ProductsInTaxonsAssigner;
use Sylius\CmsPlugin\Fixture\Assigner\TaxonsAssigner;
use Sylius\CmsPlugin\Fixture\BlockFixture;
use Sylius\CmsPlugin\Fixture\CollectionFixture;
use Sylius\CmsPlugin\Fixture\Factory\BlockFixtureFactory;
use Sylius\CmsPlugin\Fixture\Factory\CollectionFixtureFactory;
use Sylius\CmsPlugin\Fixture\Factory\MediaFixtureFactory;
use Sylius\CmsPlugin\Fixture\Factory\PageFixtureFactory;
use Sylius\CmsPlugin\Fixture\Factory\TemplateFixtureFactory;
use Sylius\CmsPlugin\Fixture\MediaFixture;
use Sylius\CmsPlugin\Fixture\PageFixture;
use Sylius\CmsPlugin\Fixture\TemplateFixture;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.fixture.assigner.channels', ChannelsAssigner::class)
        ->args([service('sylius.repository.channel')]);

    $services->set('sylius_cms.fixture.assigner.products', ProductsAssigner::class)
        ->args([service('sylius.repository.product')]);

    $services->set('sylius_cms.fixture.assigner.taxons', TaxonsAssigner::class)
        ->args([service('sylius.repository.taxon')]);

    $services->set('sylius_cms.fixture.assigner.collections', CollectionsAssigner::class)
        ->args([service('sylius_cms.repository.collection')]);

    $services->set('sylius_cms.fixture.assigner.products_in_taxons', ProductsInTaxonsAssigner::class)
        ->args([service('sylius.repository.taxon')]);

    $services->set('sylius_cms.fixture.block', BlockFixture::class)
        ->args([service('sylius_cms.fixture.factory.block')])
        ->tag('sylius_fixtures.fixture');

    $services->set('sylius_cms.fixture.page', PageFixture::class)
        ->args([service('sylius_cms.fixture.factory.page')])
        ->tag('sylius_fixtures.fixture');

    $services->set('sylius_cms.fixture.collection', CollectionFixture::class)
        ->args([service('sylius_cms.fixture.factory.collection')])
        ->tag('sylius_fixtures.fixture');

    $services->set('sylius_cms.fixture.media', MediaFixture::class)
        ->args([service('sylius_cms.fixture.factory.media')])
        ->tag('sylius_fixtures.fixture');

    $services->set('sylius_cms.fixture.template', TemplateFixture::class)
        ->args([service('sylius_cms.fixture.factory.template')])
        ->tag('sylius_fixtures.fixture');

    $services->set('sylius_cms.fixture.factory.block', BlockFixtureFactory::class)
        ->args([
            service('sylius_cms.factory.block'),
            service('sylius_cms.repository.block'),
            service('sylius_cms.fixture.assigner.collections'),
            service('sylius_cms.fixture.assigner.channels'),
            service('sylius_cms.fixture.assigner.products'),
            service('sylius_cms.fixture.assigner.taxons'),
            service('sylius_cms.fixture.assigner.products_in_taxons'),
        ]);

    $services->set('sylius_cms.fixture.factory.page', PageFixtureFactory::class)
        ->args([
            service('sylius_cms.factory.page'),
            service('sylius_cms.factory.page_translation'),
            service('sylius_cms.repository.page'),
            service('sylius_cms.repository.media'),
            service('sylius_cms.fixture.assigner.collections'),
            service('sylius_cms.fixture.assigner.channels'),
        ]);

    $services->set('sylius_cms.fixture.factory.collection', CollectionFixtureFactory::class)
        ->args([
            service('sylius_cms.factory.collection'),
            service('sylius_cms.repository.collection'),
            service('sylius_cms.repository.page'),
        ]);

    $services->set('sylius_cms.fixture.factory.media', MediaFixtureFactory::class)
        ->args([
            service('sylius_cms.factory.media'),
            service('sylius_cms.factory.media_translation'),
            service('sylius_cms.resolver.media_provider'),
            service('sylius_cms.repository.media'),
            service('sylius_cms.fixture.assigner.collections'),
            service('sylius_cms.fixture.assigner.channels'),
        ]);

    $services->set('sylius_cms.fixture.factory.template', TemplateFixtureFactory::class)
        ->args([
            service('sylius_cms.factory.template'),
            service('sylius_cms.repository.template'),
        ]);
};
