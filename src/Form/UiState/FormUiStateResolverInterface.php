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
interface FormUiStateResolverInterface
{
    /**
     * @param array<string, string> $activeElements
     *
     * @return array{
     *     active_locale: string,
     *     locales: list<string>,
     *     locale_errors: array<string, int>,
     *     active_elements: array<string, string|null>,
     *     element_errors: array<string, array<int|string, int>>,
     *     element_counts: array<string, int>,
     *     locale_warnings: array<string, array<string, string>>,
     *     tab_warnings: array<string, bool>,
     * }
     */
    public function resolve(
        FormView $form,
        FormUiStateConfiguration $configuration,
        string $activeLocale,
        array $activeElements,
    ): array;
}
