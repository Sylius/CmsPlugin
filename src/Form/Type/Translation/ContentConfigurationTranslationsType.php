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

namespace Sylius\CmsPlugin\Form\Type\Translation;

use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContentConfigurationTranslationsType extends AbstractType implements DataMapperInterface
{
    /** @var string[] */
    private array $definedLocalesCodes;

    private string $defaultLocaleCode;

    public function __construct(TranslationLocaleProviderInterface $localeProvider)
    {
        $this->definedLocalesCodes = $localeProvider->getDefinedLocalesCodes();
        $this->defaultLocaleCode = $localeProvider->getDefaultLocaleCode();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entries' => $this->definedLocalesCodes,
            'entry_name' => function (string $localeCode): string {
                return $localeCode;
            },
            'entry_options' => function (string $localeCode): array {
                return [
                    'required' => $localeCode === $this->defaultLocaleCode,
                ];
            },
        ]);
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        $translationsData = [];
        /** @var ContentConfigurationInterface $elementConfiguration */
        foreach ($viewData as $elementConfiguration) {
            $locale = $elementConfiguration->getLocale();
            if (null !== $locale) {
                $translationsData[$locale][] = $elementConfiguration;
            }
        }

        /** @var array<string, FormInterface> $formsArray */
        $formsArray = iterator_to_array($forms);
        foreach ($formsArray as $localeCode => $form) {
            if (false === isset($translationsData[$localeCode])) {
                continue;
            }

            $form->setData(array_merge(
                $form->getData() ?? [],
                ['contentElements' => $translationsData[$localeCode]],
            ));
        }
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        $data = [];
        $formsArray = iterator_to_array($forms);
        foreach ($formsArray as $localeCode => $form) {
            foreach ($form->getData()['contentElements'] ?? [] as $elementData) {
                if (null === $elementData->getLocale()) {
                    $elementData->setLocale($localeCode);
                }

                $data[] = $elementData;
            }
        }

        $viewData = $data;
    }

    public function getParent(): string
    {
        return FixedCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_configuration_translations';
    }
}
