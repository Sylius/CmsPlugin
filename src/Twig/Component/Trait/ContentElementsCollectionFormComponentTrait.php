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
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\ContentElementConfigurationType;
use Sylius\CmsPlugin\Form\Type\Translation\ContentConfigurationTranslationsType;
use Sylius\CmsPlugin\Repository\TemplateRepositoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

/**
 * @mixin ComponentWithFormTrait
 * @mixin LiveCollectionTrait
 *
 * @see ContentConfigurationTranslationsType
 */
trait ContentElementsCollectionFormComponentTrait
{
    /** @var TemplateRepositoryInterface<TemplateInterface> */
    protected TemplateRepositoryInterface $templateRepository;

    /** @var array<int|string, TemplateInterface> */
    protected array $templatesCache = [];

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
    public function applyContentTemplate(#[LiveArg] string $localeCode): void
    {
        $templateId = $this->formValues['contentElements'][$localeCode]['template'] ?? null;
        $template = $this->getTemplateElements($templateId);
        if (null === $template) {
            return;
        }

        $this->populateElements($localeCode, $template);
    }

    #[LiveAction]
    public function copyContentElements(#[LiveArg] string $localeCode, #[LiveArg] string $sourceLocaleCode): void
    {
        $elements = $this->formValues['contentElements'][$sourceLocaleCode]['contentElements'] ?? null;
        if ($localeCode === $sourceLocaleCode || !\is_array($elements) || !isset($this->formValues['contentElements'][$localeCode])) {
            return;
        }

        $copies = [];
        foreach ($elements as $element) {
            if (\is_array($element)) {
                unset($element[ContentElementConfigurationType::KEY_FIELD]);
            }

            $copies[] = $element;
        }

        $this->formValues['contentElements'][$localeCode]['contentElements'] = $copies;
        $this->onCollectionItemActivated(sprintf('[contentElements][%s][contentElements]', $localeCode), null);
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

        $copy = $data[$index];
        if (\is_array($copy)) {
            unset($copy[ContentElementConfigurationType::KEY_FIELD]);
        }

        $key = $this->insertItem($data, $copy, $index);

        $propertyAccessor->setValue($this->formValues, $propertyPath, $data);
        $this->onCollectionItemActivated($propertyPath, $key);
    }

    protected function onCollectionItemActivated(string $propertyPath, int|string|null $key): void
    {
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

    /** @param TemplateRepositoryInterface<TemplateInterface> $templateRepository */
    protected function initializeTemplateRepository(TemplateRepositoryInterface $templateRepository): void
    {
        $this->templateRepository = $templateRepository;
    }

    protected function populateElements(string $locale, ?TemplateInterface $template): void
    {
        if (null === $template) {
            return;
        }

        $this->formValues['contentElements'][$locale]['contentElements'] = [];

        foreach ($template->getContentElements() as $element) {
            $this->formValues['contentElements'][$locale]['contentElements'][] = [
                'type' => $element['type'],
            ];
        }

        $this->onCollectionItemActivated(sprintf('[contentElements][%s][contentElements]', $locale), null);
        $this->submitForm();
    }

    protected function getTemplateElements(mixed $templateId): ?TemplateInterface
    {
        if (null !== $templateId && '' !== $templateId && !isset($this->templatesCache[$templateId])) {
            $template = $this->templateRepository->find($templateId);
            if (null === $template) {
                return null;
            }

            $this->templatesCache[$templateId] = $template;
        }

        return $this->templatesCache[$templateId] ?? null;
    }
}
