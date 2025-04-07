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

namespace Sylius\CmsPlugin\Assigner;

use Sylius\CmsPlugin\Entity\CollectibleInterface;

interface CollectionsAssignerInterface
{
    /** @param array<string> $collectionsCodes */
    public function assign(CollectibleInterface $collectionsAware, array $collectionsCodes): void;
}
