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

use Tests\Sylius\CmsPlugin\Behat\Service\RandomStringGenerator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $container->import('services/api.php');
    $container->import('services/contexts.php');
    $container->import('services/elements.php');
    $container->import('services/pages.php');

    $services->defaults()
        ->public();

    $services->set('sylius_cms.behat.random_string_generator', RandomStringGenerator::class);
};
