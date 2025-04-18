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

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\Type\MediaAutocompleteType;
use Sylius\CmsPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class PageTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slug', TextType::class, [
                'label' => 'sylius_cms.ui.slug',
            ])
            ->add('title', TextType::class, [
                'label' => 'sylius_cms.ui.meta_title',
                'required' => false,
            ])
            ->add('metaKeywords', TextareaType::class, [
                'label' => 'sylius_cms.ui.meta_keywords',
                'required' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'sylius_cms.ui.meta_description',
                'required' => false,
            ])
            ->add('teaserTitle', TextType::class, [
                'label' => 'sylius_cms.ui.teaser.title',
                'required' => false,
            ])
            ->add('teaserContent', WysiwygType::class, [
                'label' => 'sylius_cms.ui.teaser.content',
                'required' => false,
            ])
            ->add('teaserImage', MediaAutocompleteType::class, [
                'label' => 'sylius_cms.ui.teaser.image',
                'required' => false,
                'type' => MediaInterface::IMAGE_TYPE,
                'extra_options' => [
                    'type' => MediaInterface::IMAGE_TYPE,
                ],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_page_translation';
    }
}
