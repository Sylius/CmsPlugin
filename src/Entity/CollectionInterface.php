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

namespace Sylius\CmsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface CollectionInterface extends
    ResourceInterface,
    PagesCollectionInterface,
    BlocksCollectionInterface,
    MediaCollectionInterface
{
    public function getCode(): ?string;

    public function setCode(?string $code): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getName(): ?string;

    public function setName(?string $name): void;
}
