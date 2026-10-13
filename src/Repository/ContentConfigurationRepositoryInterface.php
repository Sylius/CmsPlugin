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

use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/** @extends RepositoryInterface<ContentConfigurationInterface> */
interface ContentConfigurationRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns the content elements of the given type with their pages and blocks loaded.
     *
     * @return array<ContentConfigurationInterface>
     */
    public function findByTypeWithOwners(string $type): array;
}
