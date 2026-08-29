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

use Sylius\CmsPlugin\Entity\ContentConfiguration;
use Sylius\CmsPlugin\Form\DataTransformer\ContentElementDataTransformerChecker;
use Sylius\CmsPlugin\Form\DataTransformer\MultipleMediaToCodesTransformer;
use Sylius\CmsPlugin\Form\Strategy\Wysiwyg\QuillStrategy;
use Sylius\CmsPlugin\Form\Strategy\Wysiwyg\TrixStrategy;
use Sylius\CmsPlugin\Form\Type\BlockAutocompleteType;
use Sylius\CmsPlugin\Form\Type\BlockType;
use Sylius\CmsPlugin\Form\Type\CollectionAutocompleteType;
use Sylius\CmsPlugin\Form\Type\CollectionType;
use Sylius\CmsPlugin\Form\Type\ContentConfigurationType;
use Sylius\CmsPlugin\Form\Type\ContentElementChoiceType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ContentElementConfigurationType;
use Sylius\CmsPlugin\Form\Type\ContentElements\MultipleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\PagesCollectionContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridByTaxonContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElements\SingleMediaContentElementType;
use Sylius\CmsPlugin\Form\Type\ContentElementType;
use Sylius\CmsPlugin\Form\Type\MediaAutocompleteType;
use Sylius\CmsPlugin\Form\Type\MediaType;
use Sylius\CmsPlugin\Form\Type\PageAutocompleteType;
use Sylius\CmsPlugin\Form\Type\PageType;
use Sylius\CmsPlugin\Form\Type\TemplateAutocompleteType;
use Sylius\CmsPlugin\Form\Type\TemplateType;
use Sylius\CmsPlugin\Form\Type\Translation\ContentConfigurationTranslationsType;
use Sylius\CmsPlugin\Form\Type\Translation\MediaTranslationType;
use Sylius\CmsPlugin\Form\Type\Translation\PageTranslationType;
use Sylius\CmsPlugin\Form\Type\WysiwygType;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('sylius_cms.content_elements.type.single_media', SingleMediaContentElementType::TYPE);
    $parameters->set('sylius_cms.content_elements.type.multiple_media', MultipleMediaContentElementType::TYPE);
    $parameters->set('sylius_cms.content_elements.type.products_carousel_by_taxon', ProductsCarouselByTaxonContentElementType::TYPE);
    $parameters->set('sylius_cms.content_elements.type.products_grid_by_taxon', ProductsGridByTaxonContentElementType::TYPE);
    $parameters->set('sylius_cms.content_elements.type.pages_collection', PagesCollectionContentElementType::TYPE);

    $services->set('sylius_cms.form.type.block', BlockType::class)
        ->args([
            '%sylius_cms.model.block.class%',
            '%sylius_cms.form.type.block.validation_groups%',
            service('sylius_cms.provider.resource_template'),
            service('sylius.provider.locale'),
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_configuration', ContentConfigurationType::class)
        ->args([
            tagged_iterator('sylius_cms.content_elements.type', indexAttribute: 'key'),
            service('twig'),
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.page', PageType::class)
        ->args([
            '%sylius_cms.model.page.class%',
            '%sylius_cms.form.type.page.validation_groups%',
            service('sylius_cms.provider.resource_template'),
            service('sylius.provider.locale'),
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.translation.page', PageTranslationType::class)
        ->args([
            '%sylius_cms.model.page_translation.class%',
            '%sylius_cms.form.type.translation.page.validation_groups%',
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.collection', CollectionType::class)
        ->args([
            '%sylius_cms.model.collection.class%',
            '%sylius_cms.form.type.collection.validation_groups%',
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.translation.media', MediaTranslationType::class)
        ->args([
            '%sylius_cms.model.media_translation.class%',
            '%sylius_cms.form.type.translation.media.validation_groups%',
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.media', MediaType::class)
        ->args([
            '%sylius_cms.model.media.class%',
            '%sylius_cms.form.type.media.validation_groups%',
            '%sylius_cms.media_providers%',
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.template', TemplateType::class)
        ->args([
            '%sylius_cms.model.template.class%',
            '%sylius_cms.form.type.template.validation_groups%',
        ])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element', ContentElementType::class)
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element.single_media', SingleMediaContentElementType::class)
        ->args([
            service('sylius_cms.repository.media'),
            service('sylius_cms.form.data_transformer.content_element_checker'),
        ])
        ->tag('sylius_cms.content_elements.type', ['key' => '%sylius_cms.content_elements.type.single_media%'])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element.multiple_media', MultipleMediaContentElementType::class)
        ->args([service('sylius_cms.form.type.data_transformer.multiple_media_to_codes')])
        ->tag('sylius_cms.content_elements.type', ['key' => '%sylius_cms.content_elements.type.multiple_media%'])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element.products_carousel_by_taxon', ProductsCarouselByTaxonContentElementType::class)
        ->args([
            service('sylius.repository.taxon'),
            service('sylius_cms.form.data_transformer.content_element_checker'),
        ])
        ->tag('sylius_cms.content_elements.type', ['key' => '%sylius_cms.content_elements.type.products_carousel_by_taxon%'])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element.products_grid_by_taxon', ProductsGridByTaxonContentElementType::class)
        ->args([
            service('sylius.repository.taxon'),
            service('sylius_cms.form.data_transformer.content_element_checker'),
        ])
        ->tag('sylius_cms.content_elements.type', ['key' => '%sylius_cms.content_elements.type.products_grid_by_taxon%'])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element.pages_collection', PagesCollectionContentElementType::class)
        ->args([
            service('sylius_cms.repository.collection'),
            service('sylius_cms.form.data_transformer.content_element_checker'),
        ])
        ->tag('sylius_cms.content_elements.type', ['key' => '%sylius_cms.content_elements.type.pages_collection%'])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.wysiwyg', WysiwygType::class)
        ->args([service('sylius_cms.resolver.wysiwyg_strategy_resolver')])
        ->call('setStrategy', ['%sylius_cms.wysiwyg_editor%'])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.data_transformer.multiple_media_to_codes', MultipleMediaToCodesTransformer::class)
        ->args([service('sylius_cms.repository.media')]);

    $services->set('sylius_cms.form.data_transformer.content_element_checker', ContentElementDataTransformerChecker::class);

    $services->set('sylius_cms.form.wysiwyg_strategy.trix', TrixStrategy::class)
        ->tag('sylius_cms.wysiwyg_strategy', ['strategy' => 'trix']);

    $services->set('sylius_cms.form.wysiwyg_strategy.quill', QuillStrategy::class)
        ->tag('sylius_cms.wysiwyg_strategy', ['strategy' => 'quill']);

    $services->set('sylius_cms.form.type.page_autocomplete', PageAutocompleteType::class)
        ->args(['%sylius_cms.model.page.class%'])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');

    $services->set('sylius_cms.form.type.block_autocomplete', BlockAutocompleteType::class)
        ->args(['%sylius_cms.model.block.class%'])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');

    $services->set('sylius_cms.form.type.media_autocomplete', MediaAutocompleteType::class)
        ->args(['%sylius_cms.model.media.class%'])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');

    $services->set('sylius_cms.form.type.collection_autocomplete', CollectionAutocompleteType::class)
        ->args(['%sylius_cms.model.collection.class%'])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');

    $services->set('sylius_cms.form.type.admin.template_autocomplete', TemplateAutocompleteType::class)
        ->args(['%sylius_cms.model.template.class%'])
        ->tag('form.type')
        ->tag('ux.entity_autocomplete_field');

    $services->set('sylius_cms.form.type.admin.content_configuration_translations', ContentConfigurationTranslationsType::class)
        ->args([service('sylius.provider.translation_locale.admin')])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.content_element_choice', ContentElementChoiceType::class)
        ->args([tagged_iterator('sylius_cms.content_elements.type', indexAttribute: 'key')])
        ->tag('form.type');

    $services->set('sylius_cms.form.type.admin.content_element_configuration', ContentElementConfigurationType::class)
        ->args([
            ContentConfiguration::class,
            '%sylius_cms.form.type.content_configuration.validation_groups%',
            tagged_iterator('sylius_cms.content_elements.type', indexAttribute: 'key'),
        ])
        ->tag('form.type');
};
