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

use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Form\Type\Translation\ContentConfigurationTranslationsType;
use Sylius\CmsPlugin\Repository\TemplateRepositoryInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

/**
 * @mixin ComponentWithFormTrait
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
    public function applyContentTemplate(#[LiveArg] string $localeCode): void
    {
        $templateId = $this->formValues['contentElements'][$localeCode]['template'] ?? null;
        $template = $this->getTemplateElements($templateId);
        if (null === $template) {
            return;
        }

        $this->populateElements($localeCode, $template);
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
