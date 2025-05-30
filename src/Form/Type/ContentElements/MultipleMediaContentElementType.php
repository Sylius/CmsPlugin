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

namespace Sylius\CmsPlugin\Form\Type\ContentElements;

use Doctrine\Common\Collections\Collection;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\Type\MediaAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class MultipleMediaContentElementType extends AbstractType
{
    public const TYPE = 'multiple_media';

    /** @param DataTransformerInterface<array<string>|null, Collection<array-key, MediaInterface>> $mediaToCodesTransformer */
    public function __construct(private DataTransformerInterface $mediaToCodesTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::TYPE, MediaAutocompleteType::class, [
                'label' => 'sylius_cms.ui.content_elements.type.' . self::TYPE,
                'multiple' => true,
            ])
        ;

        $builder->get(self::TYPE)->addModelTransformer($this->mediaToCodesTransformer);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_content_elements_' . self::TYPE;
    }
}
