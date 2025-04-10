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

use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;

/**
 * @template T of TemplateInterface
 *
 * @extends RepositoryInterface<T>
 */
interface TemplateRepositoryInterface extends RepositoryInterface
{
    /** @return array<ResourceInterface> */
    public function findTemplatesByNamePart(string $phrase, string $type): array;
}
