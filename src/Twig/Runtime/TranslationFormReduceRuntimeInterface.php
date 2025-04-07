<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Twig\Runtime;

use Symfony\Component\Form\FormView;

interface TranslationFormReduceRuntimeInterface
{
    /**
     * @param array<string> $fields
     *
     * @return array<int|string, array<string, FormView>>
     */
    public function reduceTranslationForm(FormView $form, array $fields): array;
}
