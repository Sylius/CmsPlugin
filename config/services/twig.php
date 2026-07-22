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

use Sylius\CmsPlugin\Twig\Parser\ContentParser;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('sylius_cms.twig.admin_functions', ['sylius_cms_render_block', 'sylius_cms_render_media']);

    $services->defaults()
        ->private();

    $services->set('sylius_cms.twig.parser.content', ContentParser::class)
        ->args([
            service('twig'),
            '%sylius_cms.twig.admin_functions%',
        ]);
};
