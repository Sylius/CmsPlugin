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

namespace Tests\Sylius\CmsPlugin\Unit\Form\UiState;

use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Form\UiState\FormUiStateConfiguration;

final class FormUiStateConfigurationTest extends TestCase
{
    public function testItReturnsTheElementsPathOfTheLocale(): void
    {
        $configuration = new FormUiStateConfiguration(['contentElements'], 'contentElements.%locale%.contentElements');

        self::assertSame('contentElements.pl_PL.contentElements', $configuration->getElementsPath('pl_PL'));
    }

    public function testItMatchesTheLocaleOfTheElementsPropertyPath(): void
    {
        $configuration = new FormUiStateConfiguration(['contentElements'], 'contentElements.%locale%.contentElements');

        self::assertSame('pl_PL', $configuration->matchElementsLocale('[contentElements][pl_PL][contentElements]'));
    }

    public function testItDoesNotMatchOtherPropertyPaths(): void
    {
        $configuration = new FormUiStateConfiguration(['contentElements'], 'contentElements.%locale%.contentElements');

        self::assertNull($configuration->matchElementsLocale('[translations][pl_PL][contentElements]'));
        self::assertNull($configuration->matchElementsLocale('[contentElements][pl_PL][contentElements][0]'));
    }

    public function testItHasNoElementsWithoutTheElementsPath(): void
    {
        $configuration = new FormUiStateConfiguration(['translations']);

        self::assertNull($configuration->getElementsPath('pl_PL'));
        self::assertNull($configuration->matchElementsLocale('[contentElements][pl_PL][contentElements]'));
    }
}
