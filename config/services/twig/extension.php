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

use Sylius\CmsPlugin\Twig\Extension\RenderBlockExtension;
use Sylius\CmsPlugin\Twig\Extension\RenderCollectionExtension;
use Sylius\CmsPlugin\Twig\Extension\RenderContentElementsExtension;
use Sylius\CmsPlugin\Twig\Extension\RenderContentExtension;
use Sylius\CmsPlugin\Twig\Extension\RenderMediaExtension;
use Sylius\CmsPlugin\Twig\Extension\RenderPageLinkExtension;
use Sylius\CmsPlugin\Twig\Extension\ResolveMediaVideoPathExtension;
use Sylius\CmsPlugin\Twig\Extension\TemplateExistsExtension;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->private()
        ->tag('twig.extension');

    $services->set('sylius_cms.twig.extension.block', RenderBlockExtension::class)
        ->args([service('sylius_cms.twig.runtime.block')]);

    $services->set('sylius_cms.twig.extension.collection', RenderCollectionExtension::class)
        ->args([service('sylius_cms.twig.runtime.collection')]);

    $services->set('sylius_cms.twig.extension.media', RenderMediaExtension::class)
        ->args([service('sylius_cms.twig.runtime.media')]);

    $services->set('sylius_cms.twig.extension.render_content', RenderContentExtension::class);

    $services->set('sylius_cms.twig.extension.render_content_elements', RenderContentElementsExtension::class);

    $services->set('sylius_cms.twig.extension.render_link', RenderPageLinkExtension::class);

    $services->set('sylius_cms.twig.extension.template_exists', TemplateExistsExtension::class)
        ->args([service('sylius_cms.twig.runtime.template_exists')]);

    $services->set('sylius_cms.twig.extension.resolve_media_video_path', ResolveMediaVideoPathExtension::class)
        ->args([service('sylius_cms.twig.runtime.resolve_media_video_path')]);
};
