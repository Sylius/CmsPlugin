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
interface LocaleWarningsProviderInterface
{
    /** @return array<string, string> translation keys of the warnings, indexed by tab */
    public function provide(FormView $form, string $locale, int $elementsCount): array;
}
