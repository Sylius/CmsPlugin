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

use Psr\Log\LoggerInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface;

final class CollectionResourceResolver implements CollectionResourceResolverInterface
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function findOrLog(string $code): ?CollectionInterface
    {
        $collection = $this->collectionRepository->findOneByCode($code);

        if (false === $collection instanceof CollectionInterface) {
            $this->logger->warning(sprintf(
                'Collection with "%s" code was not found in the database.',
                $code,
            ));

            return null;
        }

        return $collection;
    }
}
