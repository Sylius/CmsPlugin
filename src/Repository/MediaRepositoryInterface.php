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

use Doctrine\ORM\QueryBuilder;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface MediaRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(string $locale): QueryBuilder;

    public function findOneEnabledByCode(
        string $code,
        string $channelCode,
    ): ?MediaInterface;

    public function findByNamePart(string $phrase, array $mediaType): array;
}
