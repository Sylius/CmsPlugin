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
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

final class CollectionType extends AbstractResourceType
{
    public const PAGE = 'page';

    public const BLOCK = 'block';

    public const MEDIA = 'media';

    /** Names of the fields holding the elements of each collection type. */
    public const ELEMENTS_FIELDS = [
        self::PAGE => 'pages',
        self::BLOCK => 'blocks',
        self::MEDIA => 'media',
    ];

    private const ELEMENTS_FIELD_TYPES = [
        'pages' => PageAutocompleteType::class,
        'blocks' => BlockAutocompleteType::class,
        'media' => MediaAutocompleteType::class,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius_cms.ui.code',
                'disabled' => null !== $builder->getData()->getCode(),
                'attr' => ['placeholder' => 'sylius_cms.ui.collection_form.code_placeholder'],
            ])
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
                'attr' => ['placeholder' => 'sylius_cms.ui.collection_form.name_placeholder'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'sylius_cms.ui.type',
                'expanded' => true,
                'choices' => [
                    'sylius_cms.ui.page' => self::PAGE,
                    'sylius_cms.ui.block' => self::BLOCK,
                    'sylius_cms.ui.media' => self::MEDIA,
                ],
            ])
        ;

        // All the element fields are kept in the form, so switching the type back and forth does not lose the selection.
        foreach (self::ELEMENTS_FIELDS as $field) {
            $builder->add($field, self::ELEMENTS_FIELD_TYPES[$field], [
                'multiple' => true,
                'required' => false,
            ]);
        }

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event): void {
            $collection = $event->getData();
            if (!$collection instanceof CollectionInterface || null === $collection->getType()) {
                return;
            }

            // A collection holds elements of a single type, so the ones of the previous type are removed.
            if (self::PAGE !== $collection->getType()) {
                $collection->getPages()?->clear();
            }
            if (self::BLOCK !== $collection->getType()) {
                $collection->getBlocks()?->clear();
            }
            if (self::MEDIA !== $collection->getType()) {
                $collection->getMedia()?->clear();
            }
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_collection';
    }
}
