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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

final class TemplateType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'sylius_cms.ui.type',
                'choices' => [
                    'sylius_cms.ui.page' => 'page',
                    'sylius_cms.ui.block' => 'block',
                ],
            ])
            ->add('contentElements', LiveCollectionType::class, [
                'entry_type' => ContentElementType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_template';
    }
}
