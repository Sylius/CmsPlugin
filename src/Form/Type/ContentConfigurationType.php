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

use Sylius\Bundle\AdminBundle\Form\Type\AddButtonType;
use Sylius\CmsPlugin\Form\Type\ContentElements\ContentElementConfigurationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

final class ContentConfigurationType extends AbstractType
{
    /** @var array<string, string> */
    private array $availableElementTypes;

    /** @param iterable<string, FormTypeInterface> $actionConfigurationTypes */
    public function __construct(iterable $actionConfigurationTypes)
    {
        foreach ($actionConfigurationTypes as $type => $formType) {
            $this->availableElementTypes[$type] = 'sylius_cms.ui.content_elements.type.' . $type;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('template', TemplateAutocompleteType::class, [
                'type' => 'page',
                'label' => 'sylius_cms.ui.content_elements.template',
                'help' => 'sylius_cms.ui.content_elements.template_help',
                'mapped' => false,
                'required' => false,
                'extra_options' => [
                    'type' => 'page',
                ],
            ])
            ->add('contentElements', LiveCollectionType::class, [
                'entry_type' => ContentElementConfigurationType::class,
                'entry_options' => [
                    'types' => $this->availableElementTypes,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'button_add_type' => AddButtonType::class,
                'button_add_options' => [
                    'label' => 'sylius_cms.ui.add_element',
                    'types' => $this->availableElementTypes,
                ],
                'button_delete_options' => [
                    'label' => false,
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', null);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_configuration';
    }
}
