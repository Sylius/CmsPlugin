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

namespace Sylius\CmsPlugin\Resolver;

use BadFunctionCallException;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final class ResourceResolver implements ResourceResolverInterface
{
    /** @param RepositoryInterface<ResourceInterface> $repository */
    public function __construct(
        private RepositoryInterface $repository,
        private FactoryInterface $factory,
        private string $uniqueColumn,
    ) {
    }

    /**
     * @throws BadFunctionCallException
     */
    public function getResource(string $identifier, string $factoryMethod = 'createNew'): ResourceInterface
    {
        $resource = $this->repository->findOneBy([$this->uniqueColumn => $identifier]);
        if (null !== $resource) {
            return $resource;
        }
        $callback = [$this->factory, $factoryMethod];

        if (is_callable($callback) && method_exists($this->factory, $factoryMethod)) {
            return $callback();
        }

        throw new BadFunctionCallException('Provided method' . $factoryMethod . ' is not callable');
    }
}
