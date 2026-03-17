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
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselContentElementType;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsCarouselContentElementRenderer;
use Sylius\Component\Core\Model\ProductInterface;
use Twig\Environment;

final class ProductsCarouselContentElementRendererTest extends TestCase
{
    /** @var ProductsProviderInterface&MockObject */
    private MockObject $productsProviderMock;

    private ProductsCarouselContentElementRenderer $productsCarouselContentElementRenderer;

    protected function setUp(): void
    {
        $this->productsProviderMock = $this->createMock(ProductsProviderInterface::class);
        $this->productsCarouselContentElementRenderer = new ProductsCarouselContentElementRenderer($this->productsProviderMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(ProductsCarouselContentElementRenderer::class, $this->productsCarouselContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->productsCarouselContentElementRenderer);
    }

    public function testSupportsProductsCarouselContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(ProductsCarouselContentElementType::TYPE);
        self::assertTrue($this->productsCarouselContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->productsCarouselContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersProductsCarouselContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var ProductInterface&MockObject $product1Mock */
        $product1Mock = $this->createMock(ProductInterface::class);
        /** @var ProductInterface&MockObject $product2Mock */
        $product2Mock = $this->createMock(ProductInterface::class);
        $template = 'custom_template';
        $this->productsCarouselContentElementRenderer->setTemplate($template);
        $this->productsCarouselContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'products_carousel' => ['products' => ['code1', 'code2']],
        ]);
        $this->productsProviderMock->expects(self::once())->method('getProductsByCodes')->with(['code1', 'code2'])->willReturn([$product1Mock, $product2Mock]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'products' => [$product1Mock, $product2Mock],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->productsCarouselContentElementRenderer->render($contentConfigurationMock));
    }

    public function testReturnsEmptyStringWhenNoProductsFound(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $this->productsCarouselContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'products_carousel' => ['products' => ['code1']],
        ]);
        $this->productsProviderMock->expects(self::once())->method('getProductsByCodes')->with(['code1'])->willReturn([]);
        $twigMock->expects(self::never())->method('render');
        self::assertSame('', $this->productsCarouselContentElementRenderer->render($contentConfigurationMock));
    }
}
