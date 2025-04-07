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

namespace Sylius\CmsPlugin\Repository;

use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;

/** @extends RepositoryInterface<BlockInterface> */
interface BlockRepositoryInterface extends RepositoryInterface
{
    public function findEnabledByCode(string $code, string $channelCode): ?BlockInterface;

    /** @return array<ResourceInterface> */
    public function findByCollectionCode(
        string $collectionCode,
        string $channelCode,
    ): array;

    /** @return array<ResourceInterface> */
    public function findByNamePart(string $phrase): array;
}
