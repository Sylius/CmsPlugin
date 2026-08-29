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

use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\BlockContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\CollectionContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\ContentCollectionContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\ContentTemplateContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\MediaContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\PageContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\QuillWysiwygContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Admin\TrixWysiwygContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Ui\Shop\HomepageBlocksContext;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.context.ui.admin.block', BlockContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.behat.current_page_resolver'),
            service('sylius.behat.notification_checker.admin'),
            service('sylius_cms.behat.page.admin.block.index'),
            service('sylius_cms.behat.page.admin.block.create'),
            service('sylius_cms.behat.page.admin.block.update'),
            service('sylius_cms.behat.random_string_generator'),
            service('sylius_cms.repository.block'),
        ]);

    $services->set('sylius_cms.behat.context.ui.admin.page', PageContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.behat.current_page_resolver'),
            service('sylius.behat.notification_checker.admin'),
            service('sylius_cms.behat.page.admin.page.index'),
            service('sylius_cms.behat.page.admin.page.create'),
            service('sylius_cms.behat.page.admin.page.update'),
            service('sylius_cms.behat.random_string_generator'),
            service('sylius_cms.repository.page'),
        ]);

    $services->set('sylius_cms.behat.context.ui.admin.content_collection', ContentCollectionContext::class)
        ->args([service('sylius_cms.behat.element.admin.content_elements_collection')]);

    $services->set('sylius_cms.behat.context.ui.admin.collection', CollectionContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.behat.current_page_resolver'),
            service('sylius.behat.notification_checker.admin'),
            service('sylius_cms.behat.page.admin.collection.index'),
            service('sylius_cms.behat.page.admin.collection.create'),
            service('sylius_cms.behat.page.admin.collection.update'),
            service('sylius_cms.behat.random_string_generator'),
        ]);

    $services->set('sylius_cms.behat.context.ui.shop.homepage_blocks', HomepageBlocksContext::class)
        ->args([service('sylius_cms.behat.page.shop.home')]);

    $services->set('sylius_cms.behat.context.ui.shop.page', \Tests\Sylius\CmsPlugin\Behat\Context\Ui\Shop\PageContext::class)
        ->args([
            service('sylius_cms.behat.page.shop.page.show'),
            service('sylius_cms.behat.page.shop.page.index'),
            service('sylius.behat.shared_storage'),
        ]);

    $services->set('sylius_cms.behat.context.ui.admin.media', MediaContext::class)
        ->args([
            service('sylius.behat.current_page_resolver'),
            service('sylius.behat.notification_checker.admin'),
            service('sylius_cms.behat.page.admin.media.index'),
            service('sylius_cms.behat.page.admin.media.create'),
            service('sylius_cms.behat.page.admin.media.update'),
            service('sylius_cms.behat.random_string_generator'),
        ]);

    $services->set('sylius_cms.behat.context.ui.shop.media', \Tests\Sylius\CmsPlugin\Behat\Context\Ui\Shop\MediaContext::class)
        ->args([
            service('sylius_cms.repository.media'),
            service('sylius_cms.behat.page.shop.home'),
        ]);

    $services->set('sylius_cms.behat.context.ui.admin.content_template', ContentTemplateContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.behat.current_page_resolver'),
            service('sylius.behat.notification_checker.admin'),
            service('sylius_cms.behat.page.admin.template.index'),
            service('sylius_cms.behat.page.admin.template.create'),
            service('sylius_cms.behat.page.admin.template.update'),
            service('sylius_cms.repository.template'),
            service('sylius_cms.behat.random_string_generator'),
        ]);

    $services->set('sylius_cms.behat.context.ui.admin.trix_wysiwyg', TrixWysiwygContext::class);

    $services->set('sylius_cms.behat.context.ui.admin.quill_wysiwyg', QuillWysiwygContext::class)
        ->args([service('sylius_cms.behat.element.admin.quill_editor')]);
};
