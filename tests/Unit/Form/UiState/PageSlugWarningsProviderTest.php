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
use Sylius\CmsPlugin\Form\UiState\PageSlugWarningsProvider;
use Symfony\Component\Form\FormView;

final class PageSlugWarningsProviderTest extends TestCase
{
    private PageSlugWarningsProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new PageSlugWarningsProvider();
    }

    public function testItWarnsAboutAMissingSlugWhenTheLocaleHasContentElements(): void
    {
        $form = $this->pageView(['slug' => '']);

        self::assertSame(
            ['seo' => 'sylius_cms.ui.page_form.warnings.slug_missing'],
            $this->provider->provide($form, 'en_US', 1),
        );
    }

    public function testItWarnsAboutAMissingSlugWhenTheLocaleHasFilledFields(): void
    {
        $form = $this->pageView(['slug' => null, 'title' => 'About us']);

        self::assertSame(
            ['seo' => 'sylius_cms.ui.page_form.warnings.slug_missing'],
            $this->provider->provide($form, 'en_US', 0),
        );
    }

    public function testItDoesNotWarnWhenTheSlugIsFilled(): void
    {
        $form = $this->pageView(['slug' => 'about-us', 'title' => 'About us']);

        self::assertSame([], $this->provider->provide($form, 'en_US', 1));
    }

    public function testItDoesNotWarnWhenTheLocaleIsNotUsed(): void
    {
        $form = $this->pageView(['slug' => '', 'title' => '']);

        self::assertSame([], $this->provider->provide($form, 'en_US', 0));
    }

    public function testItDoesNotWarnAboutAnUnknownLocale(): void
    {
        $form = $this->pageView(['slug' => '']);

        self::assertSame([], $this->provider->provide($form, 'pl_PL', 1));
    }

    /** @param array<string, mixed> $values */
    private function pageView(array $values): FormView
    {
        $translation = new FormView();
        foreach ($values as $field => $value) {
            $translation->children[$field] = new FormView($translation);
            $translation->children[$field]->vars['value'] = $value;
        }

        $translations = new FormView();
        $translations->children['en_US'] = $translation;

        $form = new FormView();
        $form->children['translations'] = $translations;

        return $form;
    }
}
