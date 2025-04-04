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

interface ContentConfigurationInterface extends ResourceInterface
{
    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;

    public function getLocale(): ?string;

    public function setLocale(?string $locale): void;

    public function getBlock(): ?BlockInterface;

    public function setBlock(?BlockInterface $block): void;

    public function getPage(): ?PageInterface;

    public function setPage(?PageInterface $page): void;
}
