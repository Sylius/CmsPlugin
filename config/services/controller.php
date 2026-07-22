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

use Sylius\CmsPlugin\Controller\Action\Admin\UploadEditorImageAction;
use Sylius\CmsPlugin\Controller\Helper\FormErrorsFlashHelper;
use Sylius\CmsPlugin\Controller\MediaController;
use Sylius\CmsPlugin\Controller\PageController;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.controller.action.admin.upload_editor_image', UploadEditorImageAction::class)
        ->args([
            service('sylius_cms.resolver.media_provider'),
            service('sylius_cms.repository.media'),
            service('sylius_cms.factory.media'),
        ]);

    $services->set('sylius_cms.controller.helper.form_errors_flash', FormErrorsFlashHelper::class)
        ->args([
            service('request_stack'),
            service('translator'),
        ]);

    $services->set('sylius_cms.controller.media.overriden', MediaController::class)
        ->parent('sylius_cms.controller.media')
        ->call('setMediaProviderResolver', [service('sylius_cms.resolver.media_provider')])
        ->call('setMediaResourceResolver', [service('sylius_cms.resolver.media_resource')])
        ->call('setFormErrorsFlashHelper', [service('sylius_cms.controller.helper.form_errors_flash')])
        ->call('setCacheManager', [service('liip_imagine.cache.manager')])
        ->call('setDataManager', [service('liip_imagine.data.manager')]);

    $services->set('sylius_cms.controller.page.overriden', PageController::class)
        ->parent('sylius_cms.controller.page')
        ->call('setFormErrorsFlashHelper', [service('sylius_cms.controller.helper.form_errors_flash')])
        ->call('setCacheManager', [service('liip_imagine.cache.manager')])
        ->call('setDataManager', [service('liip_imagine.data.manager')]);
};
