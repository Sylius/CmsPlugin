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

namespace Sylius\CmsPlugin\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\CmsPlugin\Fixture\Factory\TemplateFixtureFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class TemplateFixture extends AbstractFixture
{
    public function __construct(private TemplateFixtureFactory $templateFixtureFactory)
    {
    }

    public function load(array $options): void
    {
        $this->templateFixtureFactory->load($options['custom']);
    }

    public function getName(): string
    {
        return 'template';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('custom')
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('remove_existing')->defaultTrue()->end()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                            ->arrayNode('content_elements')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('type')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
