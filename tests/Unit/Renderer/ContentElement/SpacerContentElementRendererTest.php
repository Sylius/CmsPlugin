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
use Sylius\CmsPlugin\Form\Type\ContentElements\SpacerContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\SpacerContentElementRenderer;
use Twig\Environment;

final class SpacerContentElementRendererTest extends TestCase
{
    private SpacerContentElementRenderer $spacerContentElementRenderer;

    protected function setUp(): void
    {
        $this->spacerContentElementRenderer = new SpacerContentElementRenderer();
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(SpacerContentElementRenderer::class, $this->spacerContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->spacerContentElementRenderer);
    }

    public function testSupportsSpacerContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(SpacerContentElementType::TYPE);
        self::assertTrue($this->spacerContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->spacerContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersSpacerContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $template = 'custom_template';
        $this->spacerContentElementRenderer->setTemplate($template);
        $this->spacerContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'spacer' => '40',
        ]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/Shop/ContentElement/index.html.twig', [
            'content_element' => $template,
            'spacer_height' => '40',
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->spacerContentElementRenderer->render($contentConfigurationMock));
    }
}
