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

namespace Tests\Sylius\CmsPlugin\Unit\Twig\Runtime;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Twig\Runtime\TranslationFormReduceRuntime;
use Symfony\Component\Form\FormView;

final class TranslationFormReduceRuntimeTest extends TestCase
{
    private TranslationFormReduceRuntime $translationFormReduceRuntime;

    protected function setUp(): void
    {
        $this->translationFormReduceRuntime = new TranslationFormReduceRuntime();
    }

    public function testReducesFormToSpecifiedFields(): void
    {
        /** @var FormView&MockObject $formMock */
        $formMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $localeFormMock */
        $localeFormMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $slugFormMock */
        $slugFormMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $titleFormMock */
        $titleFormMock = $this->createMock(FormView::class);

        $localeFormMock->children = [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
            'metaDescription' => new FormView(),
        ];
        $formMock->children = [
            'en_US' => $localeFormMock,
        ];

        $result = $this->translationFormReduceRuntime->reduceTranslationForm($formMock, ['slug', 'title']);

        self::assertArrayHasKey('en_US', $result);
        self::assertSame($result['en_US'], [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
        ]);
    }

    public function testThrowsExceptionIfFieldIsNotFound(): void
    {
        /** @var FormView&MockObject $formMock */
        $formMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $localeFormMock */
        $localeFormMock = $this->createMock(FormView::class);

        $localeFormMock->children = [
            'metaDescription' => new FormView(),
        ];
        $formMock->children = [
            'en_US' => $localeFormMock,
        ];

        $this->expectException(InvalidArgumentException::class);

        $this->translationFormReduceRuntime->reduceTranslationForm($formMock, ['slug', 'title']);
    }

    public function testHandlesMultipleLocales(): void
    {
        /** @var FormView&MockObject $formMock */
        $formMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $enLocaleMock */
        $enLocaleMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $deLocaleMock */
        $deLocaleMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $slugFormMock */
        $slugFormMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $titleFormMock */
        $titleFormMock = $this->createMock(FormView::class);

        $enLocaleMock->children = [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
        ];
        $deLocaleMock->children = [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
        ];
        $formMock->children = [
            'en_US' => $enLocaleMock,
            'de_DE' => $deLocaleMock,
        ];

        $result = $this->translationFormReduceRuntime->reduceTranslationForm($formMock, ['slug', 'title']);

        self::assertCount(2, $result);
        self::assertArrayHasKey('en_US', $result);
        self::assertArrayHasKey('de_DE', $result);
        self::assertSame($result['en_US'], [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
        ]);
        self::assertSame($result['de_DE'], [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
        ]);
    }

    public function testThrowsExceptionIfFieldIsNotPresentInMultipleLocales(): void
    {
        /** @var FormView&MockObject $formMock */
        $formMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $enLocaleMock */
        $enLocaleMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $deLocaleMock */
        $deLocaleMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $slugFormMock */
        $slugFormMock = $this->createMock(FormView::class);
        $formMock->children = [
            'en_US' => $enLocaleMock,
            'de_DE' => $deLocaleMock,
        ];
        $enLocaleMock->children = [
            'slug' => $slugFormMock,
        ];
        $deLocaleMock->children = [
            'slug' => $slugFormMock,
            // 'title' is missing in de_DE
        ];
        $this->expectException(InvalidArgumentException::class);

        $this->translationFormReduceRuntime->reduceTranslationForm($formMock, ['slug', 'title']);
    }

    public function testHandlesEmptyFieldArray(): void
    {
        /** @var FormView&MockObject $formMock */
        $formMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $localeFormMock */
        $localeFormMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $slugFormMock */
        $slugFormMock = $this->createMock(FormView::class);
        /** @var FormView&MockObject $titleFormMock */
        $titleFormMock = $this->createMock(FormView::class);

        $localeFormMock->children = [
            'slug' => $slugFormMock,
            'title' => $titleFormMock,
        ];
        $formMock->children = [
            'en_US' => $localeFormMock,
        ];

        self::assertCount(0, $this->translationFormReduceRuntime->reduceTranslationForm($formMock, []));
    }
}
