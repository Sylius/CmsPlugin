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

namespace Sylius\CmsPlugin\Twig\Component\Trait;

use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

/**
 * @experimental
 *
 * @mixin ComponentWithFormTrait
 */
trait FormUiStateComponentTrait
{
    public const LOCALE_PLACEHOLDER = '%locale%';

    #[LiveProp(writable: true)]
    public string $activeLocale = '';

    /** @var array<string, string> */
    #[LiveProp(writable: true)]
    public array $activeElements = [];

    /**
     * @return list<string>
     */
    abstract protected function getUiStateTranslationsPaths(): array;

    abstract protected function getUiStateElementsPath(): string;

    /**
     * @return array{
     *     active_locale: string,
     *     locales: list<string>,
     *     locale_errors: array<string, int>,
     *     active_elements: array<string, string|null>,
     *     element_errors: array<string, array<string, int>>,
     *     element_counts: array<string, int>,
     *     locale_warnings: array<string, array<string, string>>,
     *     tab_warnings: array<string, bool>,
     * }
     */
    public function getUiState(): array
    {
        $form = $this->getFormView();
        $translationsPaths = $this->getUiStateTranslationsPaths();
        $locales = array_values(array_map('strval', array_keys($this->findFormView($form, $translationsPaths[0] ?? '')?->children ?? [])));

        $localeErrors = [];
        foreach ($locales as $locale) {
            $localeErrors[$locale] = 0;
            foreach ($translationsPaths as $path) {
                $localeErrors[$locale] += $this->countFormErrors($this->findFormView($form, $path . '.' . $locale));
            }
        }

        $activeElements = [];
        $elementErrors = [];
        $elementCounts = [];
        foreach ($locales as $locale) {
            $elements = $this->findFormView($form, str_replace(self::LOCALE_PLACEHOLDER, $locale, $this->getUiStateElementsPath()))?->children ?? [];
            $keys = [];
            $elementErrors[$locale] = [];
            foreach ($elements as $key => $element) {
                $keys[] = (string) $key;
                $errors = $this->countFormErrors($element);
                if ($errors > 0) {
                    $elementErrors[$locale][(string) $key] = $errors;
                }
            }

            $elementCounts[$locale] = \count($keys);
            $activeElements[$locale] = $this->resolveActive(
                $this->activeElements[$locale] ?? '',
                $keys,
                array_keys($elementErrors[$locale]),
            );
        }

        $localeWarnings = $this->getUiStateLocaleWarnings($form, $locales, $elementCounts);
        $tabWarnings = [];
        foreach ($localeWarnings as $warnings) {
            foreach (array_keys($warnings) as $tab) {
                $tabWarnings[$tab] = true;
            }
        }

        return [
            'active_locale' => $this->resolveActive($this->activeLocale, $locales, array_keys(array_filter($localeErrors))) ?? '',
            'locales' => $locales,
            'locale_errors' => $localeErrors,
            'active_elements' => $activeElements,
            'element_errors' => $elementErrors,
            'element_counts' => $elementCounts,
            'locale_warnings' => $localeWarnings,
            'tab_warnings' => $tabWarnings,
        ];
    }

    protected function activateElement(string $collectionPropertyPath, int|string|null $key): void
    {
        $pattern = preg_quote('[' . str_replace('.', '][', $this->getUiStateElementsPath()) . ']', '/');
        $pattern = str_replace(preg_quote(self::LOCALE_PLACEHOLDER, '/'), '(?<locale>[^\]]+)', $pattern);
        if (1 !== preg_match('/^' . $pattern . '$/', $collectionPropertyPath, $matches)) {
            return;
        }

        if (null === $key) {
            unset($this->activeElements[$matches['locale']]);

            return;
        }

        $this->activeElements[$matches['locale']] = (string) $key;
    }

    /**
     * @param list<string> $locales
     * @param array<string, int> $elementCounts
     *
     * @return array<string, array<string, string>>
     */
    protected function getUiStateLocaleWarnings(FormView $form, array $locales, array $elementCounts): array
    {
        return [];
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

    private function findFormView(FormView $form, string $path): ?FormView
    {
        foreach (explode('.', $path) as $name) {
            if (!isset($form->children[$name])) {
                return null;
            }

            $form = $form->children[$name];
        }

        return $form;
    }

    private function countFormErrors(?FormView $form): int
    {
        if (null === $form) {
            return 0;
        }

        $count = \count($form->vars['errors'] ?? []);
        foreach ($form->children as $child) {
            $count += $this->countFormErrors($child);
        }

        return $count;
    }
}
