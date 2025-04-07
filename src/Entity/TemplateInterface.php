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

interface TemplateInterface extends ResourceInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    /** @return array<array-key, mixed> */
    public function getContentElements(): array;

    /** @param array<array-key, mixed> $contentElements */
    public function setContentElements(array $contentElements): void;
}
