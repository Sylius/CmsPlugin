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

namespace Sylius\CmsPlugin\Repository;

use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface BlockRepositoryInterface extends RepositoryInterface
{
    public function findEnabledByCode(string $code, string $channelCode): ?BlockInterface;

    public function findByCollectionCode(
        string $collectionCode,
        string $channelCode,
    ): array;

    public function findByNamePart(string $phrase): array;
}
