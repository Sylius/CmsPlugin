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

namespace Sylius\CmsPlugin\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\CmsPlugin\Fixture\Factory\FixtureFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class CollectionFixture extends AbstractFixture
{
    public function __construct(private FixtureFactoryInterface $collectionFixtureFactory)
    {
    }

    public function load(array $options): void
    {
        $this->collectionFixtureFactory->load($options['custom']);
    }

    public function getName(): string
    {
        return 'collection';
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
                            ->arrayNode('page_codes')->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
