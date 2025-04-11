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
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\MultipleMediaContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\MultipleMediaContentElementRenderer;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderMediaRuntimeInterface;
use Twig\Environment;

final class MultipleMediaContentElementRendererTest extends TestCase
{
    /** @var RenderMediaRuntimeInterface&MockObject */
    private MockObject $renderMediaRuntimeMock;

    /** @var MediaRepositoryInterface&MockObject */
    private MockObject $mediaRepositoryMock;

    private MultipleMediaContentElementRenderer $multipleMediaContentElementRenderer;

    protected function setUp(): void
    {
        $this->renderMediaRuntimeMock = $this->createMock(RenderMediaRuntimeInterface::class);
        $this->mediaRepositoryMock = $this->createMock(MediaRepositoryInterface::class);
        $this->multipleMediaContentElementRenderer = new MultipleMediaContentElementRenderer($this->renderMediaRuntimeMock, $this->mediaRepositoryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(MultipleMediaContentElementRenderer::class, $this->multipleMediaContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->multipleMediaContentElementRenderer);
    }

    public function testSupportsMultipleMediaContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(MultipleMediaContentElementType::TYPE);

        self::assertTrue($this->multipleMediaContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');

        self::assertFalse($this->multipleMediaContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersMultipleMediaContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var MediaInterface&MockObject $media1Mock */
        $media1Mock = $this->createMock(MediaInterface::class);
        /** @var MediaInterface&MockObject $media2Mock */
        $media2Mock = $this->createMock(MediaInterface::class);

        $template = 'custom_template';

        $this->multipleMediaContentElementRenderer->setTemplate($template);
        $this->multipleMediaContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'multiple_media' => ['code1', 'code2'],
        ]);
        $this->mediaRepositoryMock->expects(self::once())->method('findBy')->with(['code' => ['code1', 'code2']])->willReturn([$media1Mock, $media2Mock]);
        $media1Mock->method('getCode')->willReturn('code1');
        $media2Mock->method('getCode')->willReturn('code2');

        $this->renderMediaRuntimeMock->method('renderMedia')->willReturnOnConsecutiveCalls('rendered media 1', 'rendered media 2');

        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'media' => [
                [
                    'renderedContent' => 'rendered media 1',
                    'entity' => $media1Mock,
                ],
                [
                    'renderedContent' => 'rendered media 2',
                    'entity' => $media2Mock,
                ],
            ],
        ])->willReturn('rendered template');

        self::assertSame('rendered template', $this->multipleMediaContentElementRenderer->render($contentConfigurationMock));
    }
}
