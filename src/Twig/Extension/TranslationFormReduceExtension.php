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

namespace Sylius\CmsPlugin\Twig\Extension;

use Sylius\CmsPlugin\Twig\Runtime\TranslationFormReduceRuntimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TranslationFormReduceExtension extends AbstractExtension
{
    public function __construct(private TranslationFormReduceRuntimeInterface $translationFormReduce)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'translation_form_reduce',
                [$this->translationFormReduce, 'reduceTranslationForm'],
                ['is_safe' => ['html']],
            ),
        ];
    }
}
