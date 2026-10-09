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
use Sylius\CmsPlugin\Twig\Component\Trait\ContentElementsCollectionFormComponentTrait;
use Sylius\CmsPlugin\Twig\Component\Trait\FormUiStateComponentTrait;
use Sylius\CmsPlugin\Twig\Component\Trait\PreviewComponentTrait;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\TranslatableInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Twig\Environment;

class FormComponent
{
    use ComponentToolsTrait;
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<PageInterface> */
    use ResourceFormComponentTrait;

    use ContentElementsCollectionFormComponentTrait;
    use FormUiStateComponentTrait;
    use PreviewComponentTrait;

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
        TemplateRepositoryInterface $templateRepository,
        Environment $twig,
        LocaleProviderInterface $localeProvider,
        string $previewTemplate,
        protected readonly SlugGeneratorInterface $slugGenerator,
    ) {
        $this->initialize($pageRepository, $formFactory, $resourceClass, $formClass);
        $this->initializeTemplateRepository($templateRepository);
        $this->initializePreview($twig, $localeProvider, $previewTemplate);
    }

    #[LiveAction]
    public function generateSlug(#[LiveArg] string $localeCode): void
    {
        $this->formValues['translations'][$localeCode]['slug'] = $this->slugGenerator->generate(
            $this->formValues['name'],
        );
    }

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
            $this->activateElement($propertyPath, $keys[$position + 1] ?? $keys[$position - 1] ?? null);
        }
    }

    protected function onCollectionItemActivated(string $propertyPath, int|string|null $key): void
    {
        $this->activateElement($propertyPath, $key);
    }

    /**
     * @param list<string> $locales
     * @param array<string, int> $elementCounts
     *
     * @return array<string, array<string, string>>
     */
    protected function getUiStateLocaleWarnings(FormView $form, array $locales, array $elementCounts): array
    {
        $warnings = [];
        foreach ($locales as $locale) {
            $translation = $form->children['translations']->children[$locale] ?? null;
            if (null === $translation) {
                continue;
            }

            $isUsed = ($elementCounts[$locale] ?? 0) > 0;
            foreach (['title', 'teaserTitle', 'teaserContent', 'metaKeywords', 'metaDescription'] as $field) {
                $isUsed = $isUsed || $this->isFilled($translation->children[$field]->vars['value'] ?? null);
            }

            if ($isUsed && !$this->isFilled($translation->children['slug']->vars['value'] ?? null)) {
                $warnings[$locale] = ['seo' => 'sylius_cms.ui.page_form.warnings.slug_missing'];
            }
        }

        return $warnings;
    }

    private function isFilled(mixed $value): bool
    {
        return null !== $value && '' !== $value && [] !== $value;
    }

    protected function getUiStateTranslationsPaths(): array
    {
        return ['translations', 'contentElements'];
    }

    protected function getUiStateElementsPath(): string
    {
        return 'contentElements.%locale%.contentElements';
    }

    protected function beforePreviewDispatch(): void
    {
        if (!$this->resource instanceof TranslatableInterface) {
            return;
        }

        $locale = $this->localeCode !== '' ? $this->localeCode : $this->defaultLocaleCode;

        $this->resource->setFallbackLocale($this->defaultLocaleCode);
        $this->resource->setCurrentLocale($locale);
    }
}
