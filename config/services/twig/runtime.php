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

use Sylius\CmsPlugin\Twig\Runtime\RenderBlockRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderCollectionRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderContentElementsRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderContentRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderMediaRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderPageLinkRuntime;
use Sylius\CmsPlugin\Twig\Runtime\ResolveMediaVideoPathRuntime;
use Sylius\CmsPlugin\Twig\Runtime\ResolveMediaVideoPathRuntimeInterface;
use Sylius\CmsPlugin\Twig\Runtime\TemplateExistsRuntime;
use Sylius\CmsPlugin\Twig\Runtime\TemplateExistsRuntimeInterface;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('sylius_cms.twig.link_template', '@SyliusCmsPlugin/shop/page/link.html.twig');

    $services->defaults()
        ->private()
        ->tag('twig.runtime');

    $services->set('sylius_cms.twig.runtime.block', RenderBlockRuntime::class)
        ->args([
            service('sylius_cms.resolver.block_resource'),
            service('twig'),
            service('sylius_cms.content_element_renderer_strategy'),
        ]);

    $services->set('sylius_cms.twig.runtime.collection', RenderCollectionRuntime::class)
        ->args([
            service('twig'),
            service('sylius_cms.resolver.collection_resource'),
            service('sylius_cms.collection_renderer_strategy'),
        ]);

    $services->set('sylius_cms.twig.runtime.media', RenderMediaRuntime::class)
        ->args([
            service('sylius_cms.resolver.media_provider'),
            service('sylius_cms.resolver.media_resource'),
        ]);

    $services->set('sylius_cms.twig.runtime.render_content', RenderContentRuntime::class)
        ->args([service('sylius_cms.twig.parser.content')]);

    $services->set('sylius_cms.twig.runtime.render_content_elements', RenderContentElementsRuntime::class)
        ->args([service('sylius_cms.content_element_renderer_strategy')]);

    $services->set('sylius_cms.twig.runtime.render_link', RenderPageLinkRuntime::class)
        ->args([
            service('sylius_cms.repository.page'),
            service('router.default'),
            '%sylius_cms.twig.link_template%',
        ]);

    $services->set('sylius_cms.twig.runtime.template_exists', TemplateExistsRuntime::class)
        ->lazy(TemplateExistsRuntimeInterface::class)
        ->args([service('twig')]);

    $services->set('sylius_cms.twig.runtime.resolve_media_video_path', ResolveMediaVideoPathRuntime::class)
        ->args([
            service('sylius_cms_media.video.storage'),
            '%sylius_cms.videos_dir%',
            '%sylius_core.public_dir%',
        ]);

    $services->alias(ResolveMediaVideoPathRuntimeInterface::class, 'sylius_cms.twig.runtime.resolve_media_video_path');
};
