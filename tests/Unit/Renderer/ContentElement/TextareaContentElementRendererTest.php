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

namespace Tests\Sylius\CmsPlugin\Unit\Renderer\ContentElement;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\TextareaContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\TextareaContentElementRenderer;
use Twig\Environment;

final class TextareaContentElementRendererTest extends TestCase
{
    private TextareaContentElementRenderer $textareaContentElementRenderer;

    protected function setUp(): void
    {
        $this->textareaContentElementRenderer = new TextareaContentElementRenderer();
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(TextareaContentElementRenderer::class, $this->textareaContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->textareaContentElementRenderer);
    }

    public function testSupportsTextareaContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(TextareaContentElementType::TYPE);
        self::assertTrue($this->textareaContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->textareaContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersTextareaContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $template = 'custom_template';
        $this->textareaContentElementRenderer->setTemplate($template);
        $this->textareaContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'textarea' => 'Textarea content',
        ]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/Shop/ContentElement/index.html.twig', [
            'content_element' => $template,
            'content' => 'Textarea content',
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->textareaContentElementRenderer->render($contentConfigurationMock));
    }
}
