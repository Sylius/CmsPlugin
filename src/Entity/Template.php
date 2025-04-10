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

namespace Sylius\CmsPlugin\Entity;

class Template implements TemplateInterface
{
    protected mixed $id = null;

    protected ?string $name = null;

    protected ?string $type = null;

    /** @var array<array-key, mixed> */
    protected array $contentElements = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getContentElements(): array
    {
        return $this->contentElements;
    }

    public function setContentElements(array $contentElements): void
    {
        $this->contentElements = $contentElements;
    }

    public function __toString(): string {
        return $this->name;
    }
}
