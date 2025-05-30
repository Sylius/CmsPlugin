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

use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Form\Normalizer\TypedQueryBuilderNormalizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField(
    alias: 'sylius_cms_admin_collection',
    route: 'sylius_admin_entity_autocomplete',
)]
final class CollectionAutocompleteType extends AbstractType
{
    public function __construct(private readonly string $collectionClass)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('type');
        $resolver->setAllowedValues('type', [null, CollectionType::BLOCK, CollectionType::MEDIA, CollectionType::PAGE]);
        $resolver->setDefaults([
            'class' => $this->collectionClass,
            'choice_value' => 'code',
            'choice_label' => fn (CollectionInterface $collection): string => (string) $collection->getName(),
        ]);
        $resolver->addNormalizer('query_builder', TypedQueryBuilderNormalizer::normalize(...));
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_collection_autocomplete_choice';
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
