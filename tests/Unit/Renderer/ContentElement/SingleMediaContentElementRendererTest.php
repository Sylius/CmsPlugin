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
use Sylius\CmsPlugin\Form\Type\ContentElements\SingleMediaContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\SingleMediaContentElementRenderer;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderMediaRuntimeInterface;
use Twig\Environment;

final class SingleMediaContentElementRendererTest extends TestCase
{
    /** @var RenderMediaRuntimeInterface&MockObject */
    private MockObject $renderMediaRuntimeMock;

    /** @var MediaRepositoryInterface&MockObject */
    private MockObject $mediaRepositoryMock;

    private SingleMediaContentElementRenderer $singleMediaContentElementRenderer;

    protected function setUp(): void
    {
        $this->renderMediaRuntimeMock = $this->createMock(RenderMediaRuntimeInterface::class);
        $this->mediaRepositoryMock = $this->createMock(MediaRepositoryInterface::class);
        $this->singleMediaContentElementRenderer = new SingleMediaContentElementRenderer($this->renderMediaRuntimeMock, $this->mediaRepositoryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(SingleMediaContentElementRenderer::class, $this->singleMediaContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->singleMediaContentElementRenderer);
    }

    public function testSupportsSingleMediaContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(SingleMediaContentElementType::TYPE);
        self::assertTrue($this->singleMediaContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->singleMediaContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersSingleMediaContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        $template = 'custom_template';
        $this->singleMediaContentElementRenderer->setTemplate($template);
        $this->singleMediaContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'single_media' => 'media_code',
        ]);
        $this->renderMediaRuntimeMock->expects(self::once())->method('renderMedia')->with('media_code')->willReturn('rendered media');
        $this->mediaRepositoryMock->expects(self::once())->method('findOneBy')->with(['code' => 'media_code'])->willReturn($mediaMock);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/Shop/ContentElement/index.html.twig', [
            'content_element' => $template,
            'media' => [
                'renderedContent' => 'rendered media',
                'entity' => $mediaMock,
            ],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->singleMediaContentElementRenderer->render($contentConfigurationMock));
    }
}
