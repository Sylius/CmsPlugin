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

use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Form\Normalizer\TypedQueryBuilderNormalizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField(
    alias: 'sylius_cms_template',
    route: 'sylius_admin_entity_autocomplete',
)]
final class TemplateAutocompleteChoiceType extends AbstractType
{
    public function __construct(private readonly string $templateClass)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('type');
        $resolver->setAllowedValues('type', ['page', 'block']);
        $resolver->setDefaults([
            'class' => $this->templateClass,
            'choice_name' => 'name',
            'choice_value' => 'id',
            'choice_label' => fn (TemplateInterface $template): string => (string) $template->getName(),
        ]);
        $resolver->setNormalizer('query_builder', TypedQueryBuilderNormalizer::normalize(...));
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
