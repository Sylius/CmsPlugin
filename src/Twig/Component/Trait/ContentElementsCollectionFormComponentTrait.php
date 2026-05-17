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

        $items = array_values($data);
        [$items[$currentPos], $items[$swapPos]] = [$items[$swapPos], $items[$currentPos]];

        // Give fresh keys to the two moved rows only, while keeping the visual (insertion)
        // order. New keys mean new DOM ids, so the Live Component re-creates exactly those
        // two rows instead of patching them in place. This keeps stateful WYSIWYG widgets
        // correct regardless of their morphing strategy: Trix opts out of morphing via
        // "data-live-ignore", while Quill builds its own DOM the server never renders - in
        // both cases an in-place patch would leave stale or corrupted content. Re-creating
        // the row triggers the editor's disconnect()/connect() cycle, which is the path it
        // is built to support. Untouched rows keep their keys (and initialized editors).
        $keys = array_keys($data);
        $freshIndex = $this->provideNewCollectionItemIndex($data);
        $keys[$currentPos] = $freshIndex;
        $keys[$swapPos] = $freshIndex + 1;

        $reordered = [];
        foreach ($items as $position => $item) {
            $reordered[$keys[$position]] = $item;
        }

        $propertyAccessor->setValue($this->formValues, $propertyPath, $reordered);
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

        $newItem = null === $type ? [] : ['type' => $type];

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

        array_splice($items, $insertPosition, 0, [$newItem]);

        // Symfony's CollectionType (via ResizeFormListener) never reorders existing form
        // children - it only appends keys it doesn't have yet, always at the end of its
        // internal list, no matter where that key sits in the submitted array. Giving only
        // the new row a fresh key therefore isn't enough to place it mid-collection: the
        // form would still render it last. Every row from the insertion point onward must
        // look "new" too, so Symfony drops and re-appends that whole tail in one pass, in
        // the order we submit it - landing it right after the untouched prefix. Rows
        // strictly before the insertion point keep their original key/DOM node untouched.
        $freshKeysNeeded = \count($items) - $insertPosition;
        $nextKey = $this->provideNewCollectionItemIndex($data);

        $keys = array_slice($keys, 0, $insertPosition);
        for ($i = 0; $i < $freshKeysNeeded; ++$i) {
            $keys[] = $nextKey + $i;
        }

        $propertyAccessor->setValue($this->formValues, $propertyPath, array_combine($keys, $items));
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
