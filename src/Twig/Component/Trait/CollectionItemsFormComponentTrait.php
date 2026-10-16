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

namespace Sylius\CmsPlugin\Twig\Component\Trait;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

/**
 * Removes, moves, inserts and duplicates the items of a live collection.
 *
 * @mixin ComponentWithFormTrait
 */
trait CollectionItemsFormComponentTrait
{
    use LiveCollectionTrait;

    #[LiveAction]
    public function removeCollectionItem(
        PropertyAccessorInterface $propertyAccessor,
        #[LiveArg]
        string $name,
        #[LiveArg]
        int|string $index,
    ): void {
        if (null === $this->formName) {
            return;
        }

        $propertyPath = $this->fieldNameToPropertyPath($name, $this->formName);
        $data = $propertyAccessor->getValue($this->formValues, $propertyPath);
        if (!\is_array($data)) {
            return;
        }

        $keys = array_keys($data);
        $position = array_search((string) $index, array_map('strval', $keys), true);
        unset($data[$index]);
        $propertyAccessor->setValue($this->formValues, $propertyPath, $data);

        if (false !== $position) {
            $this->onCollectionItemActivated($propertyPath, $keys[$position + 1] ?? $keys[$position - 1] ?? null);
        }
    }

    #[LiveAction]
    public function moveCollectionItem(
        PropertyAccessorInterface $propertyAccessor,
        #[LiveArg]
        string $name,
        #[LiveArg]
        int $index,
        #[LiveArg]
        string $direction,
    ): void {
        if (null === $this->formName) {
            return;
        }

        $propertyPath = $this->fieldNameToPropertyPath($name, $this->formName);
        $data = $propertyAccessor->getValue($this->formValues, $propertyPath);

        if (!\is_array($data)) {
            return;
        }

        $keys = array_keys($data);
        $currentPos = array_search($index, $keys, true);

        if (false === $currentPos) {
            return;
        }

        $swapPos = 'up' === $direction ? $currentPos - 1 : $currentPos + 1;

        if ($swapPos < 0 || $swapPos >= \count($keys)) {
            return;
        }

        $swapKey = $keys[$swapPos];
        [$data[$index], $data[$swapKey]] = [$data[$swapKey], $data[$index]];

        $propertyAccessor->setValue($this->formValues, $propertyPath, $data);
        $this->onCollectionItemActivated($propertyPath, $swapKey);
    }

    #[LiveAction]
    public function insertCollectionItem(
        PropertyAccessorInterface $propertyAccessor,
        #[LiveArg]
        string $name,
        #[LiveArg]
        ?string $type = null,
        #[LiveArg]
        ?int $insertAfterIndex = null,
    ): void {
        if (null === $this->formName) {
            return;
        }

        $propertyPath = $this->fieldNameToPropertyPath($name, $this->formName);
        $data = $propertyAccessor->getValue($this->formValues, $propertyPath);

        if (!\is_array($data)) {
            $data = [];
        }

        $key = $this->insertItem($data, null === $type ? [] : ['type' => $type], $insertAfterIndex);

        $propertyAccessor->setValue($this->formValues, $propertyPath, $data);
        $this->onCollectionItemActivated($propertyPath, $key);
    }

    #[LiveAction]
    public function duplicateCollectionItem(
        PropertyAccessorInterface $propertyAccessor,
        #[LiveArg]
        string $name,
        #[LiveArg]
        int $index,
    ): void {
        if (null === $this->formName) {
            return;
        }

        $propertyPath = $this->fieldNameToPropertyPath($name, $this->formName);
        $data = $propertyAccessor->getValue($this->formValues, $propertyPath);

        if (!\is_array($data) || !\array_key_exists($index, $data)) {
            return;
        }

        $key = $this->insertItem($data, $this->copyCollectionItem($data[$index]), $index);

        $propertyAccessor->setValue($this->formValues, $propertyPath, $data);
        $this->onCollectionItemActivated($propertyPath, $key);
    }

    abstract protected function onCollectionItemActivated(string $propertyPath, int|string|null $key): void;

    /** Prepares the values of a duplicated item, e.g. drops the ones that must stay unique. */
    protected function copyCollectionItem(mixed $item): mixed
    {
        return $item;
    }

    /**
     * @param array<int, mixed> $data
     */
    protected function insertItem(array &$data, mixed $item, ?int $insertAfterIndex): int
    {
        $keys = array_keys($data);
        $items = array_values($data);

        if (null === $insertAfterIndex) {
            $insertPosition = \count($items);
        } elseif ($insertAfterIndex < 0) {
            $insertPosition = 0;
        } else {
            $pos = array_search($insertAfterIndex, $keys, true);
            $insertPosition = false !== $pos ? $pos + 1 : \count($items);
        }

        array_splice($items, $insertPosition, 0, [$item]);

        $freshKeysNeeded = \count($items) - $insertPosition;
        $nextKey = $this->provideNewCollectionItemIndex($data);

        $keys = array_slice($keys, 0, $insertPosition);
        for ($i = 0; $i < $freshKeysNeeded; ++$i) {
            $keys[] = $nextKey + $i;
        }

        $data = array_combine($keys, $items);

        return $nextKey;
    }
}
