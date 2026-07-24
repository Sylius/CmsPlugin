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

use Tests\Sylius\CmsPlugin\Behat\Context\Transform\BlockContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Transform\CollectionContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Transform\MediaContext;
use Tests\Sylius\CmsPlugin\Behat\Context\Transform\PageContext;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.context.transform.block', BlockContext::class)
        ->args([
            service('sylius_cms.repository.block'),
            '%locale%',
        ]);

    $services->set('sylius_cms.behat.context.transform.media', MediaContext::class)
        ->args([
            service('sylius_cms.repository.media'),
            service('sylius.behat.shared_storage'),
        ]);

    $services->set('sylius_cms.behat.context.transform.page', PageContext::class)
        ->args([
            service('sylius_cms.repository.page'),
            '%locale%',
        ]);

    $services->set('sylius_cms.behat.context.transform.collection', CollectionContext::class)
        ->args([
            service('sylius_cms.repository.collection'),
            '%locale%',
        ]);
};
