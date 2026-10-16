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

namespace Sylius\CmsPlugin\Form\UiState;

use Symfony\Component\Form\FormView;

/** @experimental */
final class PageSlugWarningsProvider implements LocaleWarningsProviderInterface
{
    private const CONTENT_FIELDS = ['title', 'teaserTitle', 'teaserContent', 'metaKeywords', 'metaDescription'];

    private const TAB = 'seo';

    private const MESSAGE = 'sylius_cms.ui.page_form.warnings.slug_missing';

    public function provide(FormView $form, string $locale, int $elementsCount): array
    {
        $translation = $form->children['translations']->children[$locale] ?? null;
        if (null === $translation || $this->isFilled($translation->children['slug']->vars['value'] ?? null)) {
            return [];
        }

        if (0 === $elementsCount && !$this->hasFilledContent($translation)) {
            return [];
        }

        return [self::TAB => self::MESSAGE];
    }

    private function hasFilledContent(FormView $translation): bool
    {
        foreach (self::CONTENT_FIELDS as $field) {
            if ($this->isFilled($translation->children[$field]->vars['value'] ?? null)) {
                return true;
            }
        }

        return false;
    }

    private function isFilled(mixed $value): bool
    {
        return null !== $value && '' !== $value && [] !== $value;
    }
}
