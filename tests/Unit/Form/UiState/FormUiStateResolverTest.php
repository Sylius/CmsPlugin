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
use Sylius\CmsPlugin\Form\UiState\FormUiStateResolver;
use Sylius\CmsPlugin\Form\UiState\LocaleWarningsProviderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;

final class FormUiStateResolverTest extends TestCase
{
    private const ELEMENTS_PATH = 'contentElements.%locale%.contentElements';

    private FormUiStateResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new FormUiStateResolver();
    }

    public function testItReturnsLocalesOfTheFirstTranslationsPath(): void
    {
        $form = $this->view([
            'translations' => $this->view(['en_US' => $this->view(), 'pl_PL' => $this->view()]),
        ]);

        $state = $this->resolver->resolve($form, new FormUiStateConfiguration(['translations']), '', []);

        self::assertSame(['en_US', 'pl_PL'], $state['locales']);
        self::assertSame('en_US', $state['active_locale']);
        self::assertSame(['en_US' => 0, 'pl_PL' => 0], $state['locale_errors']);
    }

    public function testItKeepsTheSelectedLocaleWhenItExists(): void
    {
        $form = $this->view([
            'translations' => $this->view([
                'en_US' => $this->view(['slug' => $this->view([], [new FormError('Invalid')])]),
                'pl_PL' => $this->view(),
            ]),
        ]);

        $state = $this->resolver->resolve($form, new FormUiStateConfiguration(['translations']), 'pl_PL', []);

        self::assertSame('pl_PL', $state['active_locale']);
    }

    public function testItActivatesTheFirstLocaleWithErrorsWhenTheSelectedOneIsUnknown(): void
    {
        $form = $this->view([
            'translations' => $this->view(['en_US' => $this->view(), 'pl_PL' => $this->view()]),
            'contentElements' => $this->view([
                'en_US' => $this->view(),
                'pl_PL' => $this->view(['template' => $this->view([], [new FormError('Invalid')])]),
            ]),
        ]);

        $state = $this->resolver->resolve(
            $form,
            new FormUiStateConfiguration(['translations', 'contentElements']),
            'de_DE',
            [],
        );

        self::assertSame('pl_PL', $state['active_locale']);
        self::assertSame(['en_US' => 0, 'pl_PL' => 1], $state['locale_errors']);
    }

    public function testItCountsElementsAndTheirErrorsPerLocale(): void
    {
        $form = $this->contentElementsView([
            'en_US' => ['0' => $this->view(), '1' => $this->view(['type' => $this->view([], [new FormError('Invalid'), new FormError('Invalid')])])],
            'pl_PL' => [],
        ]);

        $state = $this->resolver->resolve($form, new FormUiStateConfiguration(['contentElements'], self::ELEMENTS_PATH), '', []);

        self::assertSame(['en_US' => 2, 'pl_PL' => 0], $state['element_counts']);
        self::assertSame(['en_US' => [1 => 2], 'pl_PL' => []], $state['element_errors']);
        self::assertSame(['en_US' => '1', 'pl_PL' => null], $state['active_elements']);
    }

    public function testItKeepsTheSelectedElementWhenItStillExists(): void
    {
        $form = $this->contentElementsView([
            'en_US' => ['0' => $this->view(), '3' => $this->view()],
        ]);

        $state = $this->resolver->resolve(
            $form,
            new FormUiStateConfiguration(['contentElements'], self::ELEMENTS_PATH),
            '',
            ['en_US' => '3'],
        );

        self::assertSame(['en_US' => '3'], $state['active_elements']);
    }

    public function testItActivatesTheFirstElementWhenTheSelectedOneHasBeenRemoved(): void
    {
        $form = $this->contentElementsView([
            'en_US' => ['0' => $this->view(), '3' => $this->view()],
        ]);

        $state = $this->resolver->resolve(
            $form,
            new FormUiStateConfiguration(['contentElements'], self::ELEMENTS_PATH),
            '',
            ['en_US' => '5'],
        );

        self::assertSame(['en_US' => '0'], $state['active_elements']);
    }

    public function testItSkipsElementsWhenTheConfigurationHasNoElementsPath(): void
    {
        $form = $this->view([
            'translations' => $this->view(['en_US' => $this->view()]),
        ]);

        $state = $this->resolver->resolve($form, new FormUiStateConfiguration(['translations']), '', []);

        self::assertSame(['en_US' => 0], $state['element_counts']);
        self::assertSame(['en_US' => null], $state['active_elements']);
    }

    public function testItCollectsWarningsOfTheLocalesAndTheirTabs(): void
    {
        $form = $this->view([
            'translations' => $this->view(['en_US' => $this->view(), 'pl_PL' => $this->view()]),
        ]);
        $warningsProvider = $this->createMock(LocaleWarningsProviderInterface::class);
        $warningsProvider
            ->method('provide')
            ->willReturnCallback(fn (FormView $form, string $locale): array => 'pl_PL' === $locale ? ['seo' => 'warning'] : [])
        ;

        $state = $this->resolver->resolve(
            $form,
            new FormUiStateConfiguration(['translations'], null, $warningsProvider),
            '',
            [],
        );

        self::assertSame(['pl_PL' => ['seo' => 'warning']], $state['locale_warnings']);
        self::assertSame(['seo' => true], $state['tab_warnings']);
    }

    /** @param array<string, array<int|string, FormView>> $elementsByLocale */
    private function contentElementsView(array $elementsByLocale): FormView
    {
        $locales = [];
        foreach ($elementsByLocale as $locale => $elements) {
            $locales[$locale] = $this->view(['contentElements' => $this->view($elements)]);
        }

        return $this->view(['contentElements' => $this->view($locales)]);
    }

    /**
     * @param array<int|string, FormView> $children
     * @param list<FormError> $errors
     */
    private function view(array $children = [], array $errors = []): FormView
    {
        $view = new FormView();
        $view->vars['errors'] = $errors;
        foreach ($children as $name => $child) {
            $child->parent = $view;
            $view->children[$name] = $child;
        }

        return $view;
    }
}
