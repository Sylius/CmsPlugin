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

use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;

/** @extends RepositoryInterface<CollectionInterface> */
interface CollectionRepositoryInterface extends RepositoryInterface
{
    /** @return array<ResourceInterface> */
    public function findByNamePart(string $phrase): array;

    /** @return array<ResourceInterface> */
    public function findByNamePartAndType(string $phrase, string $type): array;

    public function findOneByCode(string $code): ?CollectionInterface;

    /** @return array<ResourceInterface> */
    public function findByCodes(string $codes): array;
}
