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

use Sylius\Behat\Service\Helper\AutocompleteHelperInterface;
use Tests\Sylius\CmsPlugin\Behat\Element\Admin\ContentElementsCollectionElement;
use Tests\Sylius\CmsPlugin\Behat\Element\Admin\QuillEditorElement;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.element.admin.content_elements_collection', ContentElementsCollectionElement::class)
        ->args([
            service('behat.mink.default_session'),
            service('behat.mink.parameters'),
            service(AutocompleteHelperInterface::class),
            '%locale%',
        ]);

    $services->set('sylius_cms.behat.element.admin.quill_editor', QuillEditorElement::class)
        ->args([
            service('behat.mink.default_session'),
            service('behat.mink.parameters'),
        ]);
};
