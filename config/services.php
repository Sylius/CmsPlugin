<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\CmsPlugin\Context\PreviewLocaleContext;
use Sylius\CmsPlugin\Doctrine\ORM\Extension\EnabledAndAvailableExtension;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\CmsPlugin\Menu\MenuReorder;
use Sylius\CmsPlugin\Provider\ResourceTemplateProvider;
use Sylius\CmsPlugin\Sorter\CollectionsSorter;
use Sylius\CmsPlugin\Uploader\MediaUploader;
use Sylius\Component\Core\Filesystem\Adapter\FlysystemFilesystemAdapter;
use Sylius\Component\Registry\ServiceRegistry;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $container->import('services/**/*.php');

    $parameters->set('sylius_cms.media_provider.interface', ProviderInterface::class);

    $services->defaults()
        ->public();

    $services->set('sylius_cms.registry.media_provider', ServiceRegistry::class)
        ->args([
            '%sylius_cms.media_provider.interface%',
            'Media provider',
        ]);

    $services->set('sylius_cms.media_uploader.image', MediaUploader::class)
        ->args([service('sylius_cms.media.filesystem.image')]);

    $services->set('sylius_cms.media_uploader.video', MediaUploader::class)
        ->args([service('sylius_cms.media.filesystem.video')]);

    $services->set('sylius_cms.media_uploader.file', MediaUploader::class)
        ->args([service('sylius_cms.media.filesystem.file')]);

    $services->set('sylius_cms.media.filesystem.image', FlysystemFilesystemAdapter::class)
        ->args([service('sylius_cms_media.image.storage')]);

    $services->set('sylius_cms.media.filesystem.video', FlysystemFilesystemAdapter::class)
        ->args([service('sylius_cms_media.video.storage')]);

    $services->set('sylius_cms.media.filesystem.file', FlysystemFilesystemAdapter::class)
        ->args([service('sylius_cms_media.file.storage')]);

    $services->set('sylius_cms.sorter.collections', CollectionsSorter::class);

    $services->set('sylius_cms.context.locale.admin_preview', PreviewLocaleContext::class)
        ->args([
            service('sylius.section_resolver.uri_based'),
            service('request_stack'),
            service('sylius.provider.locale'),
        ])
        ->tag('sylius.context.locale', ['priority' => 256]);

    $services->set('sylius_cms.provider.resource_template', ResourceTemplateProvider::class)
        ->args([service('parameter_bag')]);

    $services->set('sylius_cms.menu.reorder', MenuReorder::class);

    $services->set(EnabledAndAvailableExtension::class)
        ->args([
            service(UserContextInterface::class),
            service('sylius.context.channel'),
        ])
        ->tag('api_platform.doctrine.orm.query_extension.collection')
        ->tag('api_platform.doctrine.orm.query_extension.item');
};
