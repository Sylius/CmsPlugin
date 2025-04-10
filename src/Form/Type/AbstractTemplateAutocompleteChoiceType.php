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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

abstract class AbstractTemplateAutocompleteChoiceType extends AbstractType
{
    public function __construct(
        private string $templactClass,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => $this->templactClass,
            'choice_name' => 'name',
            'choice_value' => 'id',
            'multiple' => true,
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
