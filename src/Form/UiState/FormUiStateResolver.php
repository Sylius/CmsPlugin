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
final class FormUiStateResolver implements FormUiStateResolverInterface
{
    public function resolve(
        FormView $form,
        FormUiStateConfiguration $configuration,
        string $activeLocale,
        array $activeElements,
    ): array {
        $locales = $this->stringKeys($this->findChild($form, $configuration->translationsPaths[0] ?? '')?->children ?? []);

        $localeErrors = [];
        $resolvedActiveElements = [];
        $elementErrors = [];
        $elementCounts = [];
        $localeWarnings = [];

        foreach ($locales as $locale) {
            $localeErrors[$locale] = 0;
            foreach ($configuration->translationsPaths as $path) {
                $localeErrors[$locale] += $this->countErrors($this->findChild($form, $path . '.' . $locale));
            }

            $elements = $this->getElements($form, $configuration, $locale);
            $elementErrors[$locale] = array_filter(array_map($this->countErrors(...), $elements));
            $elementCounts[$locale] = \count($elements);
            $resolvedActiveElements[$locale] = $this->resolveActive(
                $activeElements[$locale] ?? '',
                $this->stringKeys($elements),
                $this->stringKeys($elementErrors[$locale]),
            );

            $warnings = $configuration->localeWarningsProvider?->provide($form, $locale, $elementCounts[$locale]) ?? [];
            if ([] !== $warnings) {
                $localeWarnings[$locale] = $warnings;
            }
        }

        $tabWarnings = [];
        foreach ($localeWarnings as $warnings) {
            $tabWarnings += array_fill_keys(array_keys($warnings), true);
        }

        return [
            'active_locale' => $this->resolveActive($activeLocale, $locales, $this->stringKeys(array_filter($localeErrors))) ?? '',
            'locales' => $locales,
            'locale_errors' => $localeErrors,
            'active_elements' => $resolvedActiveElements,
            'element_errors' => $elementErrors,
            'element_counts' => $elementCounts,
            'locale_warnings' => $localeWarnings,
            'tab_warnings' => $tabWarnings,
        ];
    }

    /** @return array<int|string, FormView> */
    private function getElements(FormView $form, FormUiStateConfiguration $configuration, string $locale): array
    {
        $path = $configuration->getElementsPath($locale);
        if (null === $path) {
            return [];
        }

        return $this->findChild($form, $path)?->children ?? [];
    }

    /**
     * @param list<string> $available
     * @param list<string> $withErrors
     */
    private function resolveActive(string $selected, array $available, array $withErrors): ?string
    {
        if (\in_array($selected, $available, true)) {
            return $selected;
        }

        return $withErrors[0] ?? $available[0] ?? null;
    }

    private function findChild(FormView $form, string $path): ?FormView
    {
        foreach (explode('.', $path) as $name) {
            if (!isset($form->children[$name])) {
                return null;
            }

            $form = $form->children[$name];
        }

        return $form;
    }

    private function countErrors(?FormView $form): int
    {
        if (null === $form) {
            return 0;
        }

        $count = \count($form->vars['errors'] ?? []);
        foreach ($form->children as $child) {
            $count += $this->countErrors($child);
        }

        return $count;
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return list<string>
     */
    private function stringKeys(array $array): array
    {
        return array_map('strval', array_keys($array));
    }
}
