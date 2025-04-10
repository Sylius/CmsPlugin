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

use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;

#[AsEntityAutocompleteField(
    alias: 'sylius_cms_admin_template_block',
    route: 'sylius_admin_entity_autocomplete',
)]
final class TemplateBlockAutocompleteChoiceType extends AbstractTemplateAutocompleteChoiceType
{
    public function __construct(
        private string $templactClass,
    ) {
        parent::__construct($this->templactClass);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_template_block_autocomplete_choice';
    }
}
