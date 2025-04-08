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
use Sylius\CmsPlugin\Form\Type\ContentElements\HeadingContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\HeadingContentElementRenderer;
use Twig\Environment;

final class HeadingContentElementRendererTest extends TestCase
{
    private HeadingContentElementRenderer $headingContentElementRenderer;

    protected function setUp(): void
    {
        $this->headingContentElementRenderer = new HeadingContentElementRenderer();
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(HeadingContentElementRenderer::class, $this->headingContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->headingContentElementRenderer);
    }

    public function testSupportsHeadingContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(HeadingContentElementType::TYPE);

        self::assertTrue($this->headingContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');

        self::assertFalse($this->headingContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersHeadingContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $template = 'custom_template';
        $this->headingContentElementRenderer->setTemplate($template);
        $this->headingContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'heading_type' => 'h1',
            'heading' => 'Sample Heading',
        ]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/Shop/ContentElement/index.html.twig', [
            'content_element' => $template,
            'heading_type' => 'h1',
            'heading_content' => 'Sample Heading',
        ])->willReturn('rendered template');

        self::assertSame('rendered template', $this->headingContentElementRenderer->render($contentConfigurationMock));
    }
}
