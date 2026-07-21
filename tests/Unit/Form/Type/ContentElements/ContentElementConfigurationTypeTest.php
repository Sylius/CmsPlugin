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

namespace Tests\Sylius\CmsPlugin\Unit\Form\Type\ContentElements;

use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\ContentConfiguration;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\ContentElementConfigurationType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class ContentElementConfigurationTypeTest extends TestCase
{
    private ContentElementConfigurationType $type;

    protected function setUp(): void
    {
        $this->type = new ContentElementConfigurationType(ContentConfiguration::class, [], []);
    }

    public function testItExposesAContentSignatureForTheElement(): void
    {
        $view = $this->buildViewFor('textarea', ['textarea' => '<p>Some content</p>']);

        self::assertArrayHasKey('content_signature', $view->vars);
        self::assertMatchesRegularExpression('/^[a-f0-9]{12}$/', $view->vars['content_signature']);
    }

    public function testTheSignatureIsStableForIdenticalContent(): void
    {
        $first = $this->buildViewFor('textarea', ['textarea' => '<p>Same content</p>']);
        $second = $this->buildViewFor('textarea', ['textarea' => '<p>Same content</p>']);

        self::assertSame($first->vars['content_signature'], $second->vars['content_signature']);
    }

    public function testTheSignatureChangesWhenTheContentChanges(): void
    {
        $first = $this->buildViewFor('textarea', ['textarea' => '<p>First</p>']);
        $second = $this->buildViewFor('textarea', ['textarea' => '<p>Second</p>']);

        self::assertNotSame($first->vars['content_signature'], $second->vars['content_signature']);
    }

    public function testTheSignatureChangesWhenTheTypeChanges(): void
    {
        $textarea = $this->buildViewFor('textarea', ['textarea' => 'value']);
        $singleMedia = $this->buildViewFor('single_media', ['single_media' => 'value']);

        self::assertNotSame($textarea->vars['content_signature'], $singleMedia->vars['content_signature']);
    }

    public function testTheSignatureIsEmptyWhenThereIsNoElementData(): void
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn(null);

        $view = new FormView();
        $this->type->buildView($view, $form, ['types' => []]);

        self::assertSame('', $view->vars['content_signature']);
    }

    /** @param array<string, mixed> $configuration */
    private function buildViewFor(string $type, array $configuration): FormView
    {
        $data = new ContentConfiguration();
        $data->setType($type);
        $data->setConfiguration($configuration);

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);
        self::assertInstanceOf(ContentConfigurationInterface::class, $data);

        $view = new FormView();
        $this->type->buildView($view, $form, ['types' => []]);

        return $view;
    }
}
