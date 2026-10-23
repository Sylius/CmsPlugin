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

final class FormUiStateConfiguration
{
    public const LOCALE_PLACEHOLDER = '%locale%';

    /**
     * @param list<string> $translationsPaths
     * @param string|null $elementsPath path of the content elements collection, with the locale replaced by `%locale%`
     */
    public function __construct(
        public readonly array $translationsPaths,
        public readonly ?string $elementsPath = null,
        public readonly ?LocaleWarningsProviderInterface $localeWarningsProvider = null,
    ) {
    }

    public function getElementsPath(string $locale): ?string
    {
        if (null === $this->elementsPath) {
            return null;
        }

        return str_replace(self::LOCALE_PLACEHOLDER, $locale, $this->elementsPath);
    }

    public function matchElementsLocale(string $propertyPath): ?string
    {
        if (null === $this->elementsPath) {
            return null;
        }

        $pattern = preg_quote('[' . str_replace('.', '][', $this->elementsPath) . ']', '/');
        $pattern = str_replace(preg_quote(self::LOCALE_PLACEHOLDER, '/'), '(?<locale>[^\]]+)', $pattern);

        return 1 === preg_match('/^' . $pattern . '$/', $propertyPath, $matches) ? $matches['locale'] : null;
    }
}
