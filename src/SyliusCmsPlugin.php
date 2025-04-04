<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\CmsPlugin\DependencyInjection\Compiler\ContentElementPass;
use Sylius\CmsPlugin\DependencyInjection\Compiler\ImporterCompilerPass;
use Sylius\CmsPlugin\DependencyInjection\Compiler\MediaProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusCmsPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ImporterCompilerPass());
        $container->addCompilerPass(new MediaProviderPass());
        $container->addCompilerPass(new ContentElementPass());
    }
}
