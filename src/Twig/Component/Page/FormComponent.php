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

namespace Sylius\CmsPlugin\Twig\Component\Page;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Repository\TemplateRepositoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentToolsTrait;

class FormComponent
{
    use ComponentToolsTrait;
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<PageInterface> */
    use ResourceFormComponentTrait;

    /** @var array<int|string, TemplateInterface> */
    protected array $templatesCache = [];

    /**
     * @param RepositoryInterface<PageInterface> $pageRepository
     * @param class-string<PageInterface> $resourceClass
     * @param class-string<AbstractType> $formClass
     * @param TemplateRepositoryInterface<TemplateInterface> $templateRepository
     */
    public function __construct(
        RepositoryInterface $pageRepository,
        FormFactoryInterface $formFactory,
        string $resourceClass,
        string $formClass,
        protected readonly SlugGeneratorInterface $slugGenerator,
        protected readonly TemplateRepositoryInterface $templateRepository,
    ) {
        $this->initialize($pageRepository, $formFactory, $resourceClass, $formClass);
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
    public function generateSlug(#[LiveArg] string $localeCode): void
    {
        $this->formValues['translations'][$localeCode]['slug'] = $this->slugGenerator->generate(
            $this->formValues['name'],
        );
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
