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

use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\Normalizer\TypedQueryBuilderNormalizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField(
    alias: 'sylius_cms_media',
    route: 'sylius_admin_entity_autocomplete',
)]
final class MediaAutocompleteType extends AbstractType
{
    public function __construct(private readonly string $mediaClass)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('type');
        $resolver->setAllowedValues('type', [
            MediaInterface::IMAGE_TYPE,
            MediaInterface::FILE_TYPE,
            MediaInterface::VIDEO_TYPE,
            null,
        ]);
        $resolver->setDefaults([
            'class' => $this->mediaClass,
            'choice_label' => 'name',
            'choice_value' => 'code',
            'type' => null,
        ]);

        $resolver->setNormalizer('query_builder', TypedQueryBuilderNormalizer::normalize(...));
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_media_autocomplete';
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
