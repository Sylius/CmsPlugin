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
        $view = $this->buildViewFor('textarea', 'abc123');

        self::assertArrayHasKey('content_signature', $view->vars);
        self::assertMatchesRegularExpression('/^[a-f0-9]{12}$/', $view->vars['content_signature']);
    }

    public function testTheSignatureIsStableForTheSameElement(): void
    {
        $first = $this->buildViewFor('textarea', 'abc123', ['textarea' => '<p>First</p>']);
        $second = $this->buildViewFor('textarea', 'abc123', ['textarea' => '<p>Second</p>']);

        self::assertSame($first->vars['content_signature'], $second->vars['content_signature']);
    }

    public function testTheSignatureChangesWhenTheElementKeyChanges(): void
    {
        $first = $this->buildViewFor('textarea', 'abc123');
        $second = $this->buildViewFor('textarea', 'def456');

        self::assertNotSame($first->vars['content_signature'], $second->vars['content_signature']);
    }

    public function testTheSignatureChangesWhenTheTypeChanges(): void
    {
        $textarea = $this->buildViewFor('textarea', 'abc123');
        $singleMedia = $this->buildViewFor('single_media', 'abc123');

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

    public function testItGeneratesUniqueKeys(): void
    {
        self::assertMatchesRegularExpression('/^[a-f0-9]{12}$/', ContentElementConfigurationType::generateKey());
        self::assertNotSame(ContentElementConfigurationType::generateKey(), ContentElementConfigurationType::generateKey());
    }

    /** @param array<string, mixed> $configuration */
    private function buildViewFor(string $type, string $key, array $configuration = []): FormView
    {
        $data = new ContentConfiguration();
        $data->setType($type);
        $data->setConfiguration($configuration);
        self::assertInstanceOf(ContentConfigurationInterface::class, $data);

        $keyField = $this->createMock(FormInterface::class);
        $keyField->method('getData')->willReturn($key);

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);
        $form->method('has')->with(ContentElementConfigurationType::KEY_FIELD)->willReturn(true);
        $form->method('get')->with(ContentElementConfigurationType::KEY_FIELD)->willReturn($keyField);

        $view = new FormView();
        $this->type->buildView($view, $form, ['types' => []]);

        return $view;
    }
}
