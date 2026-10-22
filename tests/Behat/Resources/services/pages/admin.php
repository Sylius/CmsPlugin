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

return static function (ContainerConfigurator $container) {
    $container->import('admin/block.php');
    $container->import('admin/page.php');
    $container->import('admin/collection.php');
    $container->import('admin/media.php');
    $container->import('admin/template.php');
};
