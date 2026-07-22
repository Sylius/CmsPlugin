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

use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponent;
use Sylius\CmsPlugin\Form\Type\BlockType;
use Sylius\CmsPlugin\Form\Type\CollectionType;
use Sylius\CmsPlugin\Form\Type\MediaType;
use Sylius\CmsPlugin\Form\Type\PageType;
use Sylius\CmsPlugin\Form\Type\TemplateType;
use Sylius\CmsPlugin\Twig\Component\Block\FormComponent;
use Sylius\CmsPlugin\Twig\Component\Page\FormComponent as PageFormComponent;
use Sylius\CmsPlugin\Twig\Component\MediaPreviewComponent;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('sylius_cms.twig.component.template.form', ResourceFormComponent::class)
        ->args([
            service('sylius_cms.repository.template'),
            service('form.factory'),
            '%sylius_cms.model.template.class%',
            TemplateType::class,
        ])
        ->tag('sylius.live_component.admin', ['key' => 'sylius_cms:admin:template:form']);

    $services->set('sylius_cms.twig.component.collection.form', ResourceFormComponent::class)
        ->args([
            service('sylius_cms.repository.collection'),
            service('form.factory'),
            '%sylius_cms.model.collection.class%',
            CollectionType::class,
        ])
        ->tag('sylius.live_component.admin', ['key' => 'sylius_cms:admin:collection:form']);

    $services->set('sylius_cms.twig.component.block.form', FormComponent::class)
        ->args([
            service('sylius_cms.repository.block'),
            service('form.factory'),
            '%sylius_cms.model.block.class%',
            BlockType::class,
            service('sylius_cms.repository.template'),
            service('twig'),
            service('sylius.provider.locale'),
            '@SyliusCmsPlugin/admin/block/preview.html.twig',
            service('sylius_cms.content_element_renderer_strategy'),
        ])
        ->call('setLiveResponder', [service('ux.live_component.live_responder')])
        ->tag('sylius.live_component.admin', ['key' => 'sylius_cms:admin:block:form']);

    $services->set('sylius_cms.twig.component.media.form', ResourceFormComponent::class)
        ->args([
            service('sylius_cms.repository.media'),
            service('form.factory'),
            '%sylius_cms.model.media.class%',
            MediaType::class,
        ])
        ->tag('sylius.live_component.admin', ['key' => 'sylius_cms:admin:media:form']);

    $services->set('sylius_cms.twig.component.media.preview', MediaPreviewComponent::class)
        ->tag('sylius.twig_component', ['key' => 'sylius_cms:admin:media:preview']);

    $services->set('sylius_cms.twig.component.admin.page.form', PageFormComponent::class)
        ->args([
            service('sylius_cms.repository.page'),
            service('form.factory'),
            '%sylius_cms.model.page.class%',
            PageType::class,
            service('sylius_cms.repository.template'),
            service('twig'),
            service('sylius.provider.locale'),
            '@SyliusCmsPlugin/admin/page/preview.html.twig',
            service('sylius.generator.slug'),
        ])
        ->call('setLiveResponder', [service('ux.live_component.live_responder')])
        ->tag('sylius.live_component.admin', ['key' => 'sylius_cms:admin:page:form']);
};
