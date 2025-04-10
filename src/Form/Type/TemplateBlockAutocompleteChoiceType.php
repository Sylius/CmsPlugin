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
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField(
    alias: 'sylius_cms_template_block',
    route: 'sylius_admin_entity_autocomplete',
)]
final class TemplateBlockAutocompleteChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('type', 'block');
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_admin_template_block_autocomplete_choice';
    }

    public function getParent(): string
    {
        return TemplateAutocompleteChoiceType::class;
    }
}
