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
    use CollectionItemsFormComponentTrait;

    /** @var TemplateRepositoryInterface<TemplateInterface> */
    protected TemplateRepositoryInterface $templateRepository;

    /** @var array<int|string, TemplateInterface> */
    protected array $templatesCache = [];

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

        $this->formValues['contentElements'][$localeCode]['contentElements'] = array_values(array_map($this->copyCollectionItem(...), $elements));
        $this->onCollectionItemActivated(sprintf('[contentElements][%s][contentElements]', $localeCode), null);
    }

    protected function copyCollectionItem(mixed $item): mixed
    {
        if (\is_array($item)) {
            unset($item[ContentElementConfigurationType::KEY_FIELD]);
        }

        return $item;
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
