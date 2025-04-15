<?php

declare(strict_types=1);

namespace Tests\Sylius\CmsPlugin\Behat\Element\Admin;

interface ContentElementsCollectionElementInterface
{
    public function hasContentElement(string $type): bool;

    public function getContentElementsCount(): int;

    public function addContentElement(string $type): void;

    public function removeContentElement(string $type): void;
}
