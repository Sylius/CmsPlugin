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

use Sylius\CmsPlugin\Provider\ProductsProvider;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\CmsPlugin\Renderer\Collection\CollectionBlocksRenderer;
use Sylius\CmsPlugin\Renderer\Collection\CollectionMediaRenderer;
use Sylius\CmsPlugin\Renderer\Collection\CollectionPagesRenderer;
use Sylius\CmsPlugin\Renderer\CollectionRendererStrategy;
use Sylius\CmsPlugin\Renderer\ContentElement\HeadingContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\MultipleMediaContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\PagesCollectionContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsCarouselByTaxonContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsCarouselContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridByTaxonContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\SingleMediaContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\SpacerContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\TaxonsListContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElement\TextareaContentElementRenderer;
use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategy;
use Sylius\CmsPlugin\Renderer\PageLinkRenderer;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.content_element_renderer_strategy', ContentElementRendererStrategy::class)
        ->args([
            service('sylius_cms.twig.parser.content'),
            service('sylius.context.locale'),
            tagged_iterator('sylius_cms.content_element'),
        ]);

    $services->set('sylius_cms.content_element.textarea', TextareaContentElementRenderer::class)
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/textarea.html.twig', 'form_type' => \Sylius\CmsPlugin\Form\Type\ContentElements\TextareaContentElementType::class]);

    $services->set('sylius_cms.content_element.single_media', SingleMediaContentElementRenderer::class)
        ->args([
            service('sylius_cms.twig.runtime.media'),
            service('sylius_cms.repository.media'),
        ])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/single_media.html.twig']);

    $services->set('sylius_cms.content_element.multiple_media', MultipleMediaContentElementRenderer::class)
        ->args([
            service('sylius_cms.twig.runtime.media'),
            service('sylius_cms.repository.media'),
        ])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/multiple_media.html.twig']);

    $services->set('sylius_cms.content_element.heading', HeadingContentElementRenderer::class)
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/heading.html.twig', 'form_type' => \Sylius\CmsPlugin\Form\Type\ContentElements\HeadingContentElementType::class]);

    $services->set('sylius_cms.products_provider', ProductsProvider::class)
        ->args([
            service('sylius.repository.product'),
            service('sylius.context.channel'),
        ]);

    $services->alias(ProductsProviderInterface::class, 'sylius_cms.products_provider');

    $services->set('sylius_cms.content_element.products_carousel', ProductsCarouselContentElementRenderer::class)
        ->args([
            service('sylius_cms.products_provider'),
            service('sylius.context.channel'),
        ])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/products_carousel.html.twig', 'form_type' => \Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselContentElementType::class]);

    $services->set('sylius_cms.content_element.products_carousel_by_taxon', ProductsCarouselByTaxonContentElementRenderer::class)
        ->args([
            service('sylius_cms.products_provider'),
            service('sylius.context.channel'),
            service('sylius.repository.taxon')->nullOnInvalid(),
        ])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/products_carousel.html.twig']);

    $services->set('sylius_cms.content_element.products_grid', ProductsGridContentElementRenderer::class)
        ->args([
            service('sylius_cms.products_provider'),
            service('sylius.context.channel'),
        ])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/products_grid.html.twig', 'form_type' => \Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridContentElementType::class]);

    $services->set('sylius_cms.content_element.products_grid_by_taxon', ProductsGridByTaxonContentElementRenderer::class)
        ->args([
            service('sylius_cms.products_provider'),
            service('sylius.context.channel'),
            service('sylius.repository.taxon')->nullOnInvalid(),
        ])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/products_grid.html.twig']);

    $services->set('sylius_cms.content_element.taxons_list', TaxonsListContentElementRenderer::class)
        ->args([service('sylius.repository.taxon')])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/taxons_list.html.twig', 'form_type' => \Sylius\CmsPlugin\Form\Type\ContentElements\TaxonsListContentElementType::class]);

    $services->set('sylius_cms.content_element.pages_collection', PagesCollectionContentElementRenderer::class)
        ->args([service('sylius_cms.repository.collection')])
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/pages_collection.html.twig']);

    $services->set('sylius_cms.content_element.spacer', SpacerContentElementRenderer::class)
        ->tag('sylius_cms.content_element', ['template' => '@SyliusCmsPlugin/shop/content_element/elements/spacer.html.twig', 'form_type' => \Sylius\CmsPlugin\Form\Type\ContentElements\SpacerContentElementType::class]);

    $services->set('sylius_cms.collection_renderer_strategy', CollectionRendererStrategy::class)
        ->args([tagged_iterator('sylius_cms.renderer.collection')]);

    $services->set('sylius_cms.renderer.collection.blocks', CollectionBlocksRenderer::class)
        ->args([service('sylius_cms.content_element_renderer_strategy')])
        ->tag('sylius_cms.renderer.collection');

    $services->set('sylius_cms.renderer.collection.media', CollectionMediaRenderer::class)
        ->args([service('sylius_cms.twig.runtime.media')])
        ->tag('sylius_cms.renderer.collection');

    $services->set('sylius_cms.renderer.collection.pages', CollectionPagesRenderer::class)
        ->args([service('sylius_cms.page_link_renderer')])
        ->tag('sylius_cms.renderer.collection');

    $services->set('sylius_cms.page_link_renderer', PageLinkRenderer::class)
        ->args([
            service('router.default'),
            service('twig'),
        ]);
};
