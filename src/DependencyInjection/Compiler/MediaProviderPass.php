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

namespace Sylius\CmsPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class MediaProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sylius_cms.registry.media_provider')) {
            return;
        }

        $providerRegistry = $container->getDefinition('sylius_cms.registry.media_provider');
        $providers = [];

        foreach ($container->findTaggedServiceIds('sylius_cms.media_provider') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged media provider needs to have `type` and `label` attribute.');
            }

            $name = $attributes[0]['label'];
            $type = $attributes[0]['type'];

            $providers[$name] = $type;

            $providerRegistry->addMethodCall('register', [$type, new Reference($id)]);
        }

        ksort($providers);

        $container->setParameter('sylius_cms.media_providers', $providers);
    }
}
