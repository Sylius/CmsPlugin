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

namespace Sylius\CmsPlugin\Form\Type;

use Sylius\Bundle\AdminBundle\Form\Type\ProductAutocompleteType;
use Sylius\Bundle\AdminBundle\Form\Type\TaxonAutocompleteType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\CmsPlugin\Form\Type\Translation\ContentConfigurationTranslationsType;
use Sylius\CmsPlugin\Provider\ResourceTemplateProviderInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class BlockType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        private readonly ResourceTemplateProviderInterface $templateProvider,
        private readonly LocaleContextInterface $localeContext,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'sylius_cms.ui.template',
                'choices' => $this->templateProvider->getBlockTemplates(),
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius_cms.ui.enabled',
                'required' => false,
            ])
            ->add('collections', CollectionAutocompleteType::class, [
                'label' => 'sylius_cms.ui.collections',
                'by_reference' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'label' => 'sylius_cms.ui.channels',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('products', ProductAutocompleteType::class, [
                'label' => 'sylius_cms.ui.display_for_products.label',
                'multiple' => true,
                'help' => 'sylius_cms.ui.display_for_products.help',
                'required' => false,
            ])
            ->add('productsInTaxons', TaxonAutocompleteType::class, [
                'label' => 'sylius_cms.ui.display_for_products_in_taxons.label',
                'multiple' => true,
                'help' => 'sylius_cms.ui.display_for_products_in_taxons.help',
                'required' => false,
            ])
            ->add('taxons', TaxonAutocompleteType::class, [
                'label' => 'sylius_cms.ui.display_for_taxons.label',
                'multiple' => true,
                'required' => false,
                'help' => 'sylius_cms.ui.display_for_taxons.help',
            ])
            ->add('localeCode', LocaleChoiceType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'placeholder' => false,
                'empty_data' => $this->localeContext->getLocaleCode(),
            ])
            ->add('contentElements', ContentConfigurationTranslationsType::class, [
                'entry_type' => ContentConfigurationType::class,
                'entry_options' => [
                    'template_type' => 'block',
                ],
                'by_reference' => false,
                'required' => false,
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_admin_block';
    }
}
