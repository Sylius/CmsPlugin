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
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridContentElementType;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridContentElementRenderer;
use Sylius\Component\Core\Model\ProductInterface;
use Twig\Environment;

final class ProductsGridContentElementRendererTest extends TestCase
{
    /** @var ProductsProviderInterface&MockObject */
    private MockObject $productsProviderMock;

    private ProductsGridContentElementRenderer $productsGridContentElementRenderer;

    protected function setUp(): void
    {
        $this->productsProviderMock = $this->createMock(ProductsProviderInterface::class);
        $this->productsGridContentElementRenderer = new ProductsGridContentElementRenderer($this->productsProviderMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(ProductsGridContentElementRenderer::class, $this->productsGridContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->productsGridContentElementRenderer);
    }

    public function testSupportsProductsGridContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(ProductsGridContentElementType::TYPE);
        self::assertTrue($this->productsGridContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->productsGridContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersProductsGridContentElement(): void
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
        $this->productsGridContentElementRenderer->setTemplate($template);
        $this->productsGridContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'products_grid' => ['products' => ['code1', 'code2']],
        ]);
        $this->productsProviderMock->expects(self::once())->method('getProductsByCodes')->with(['code1', 'code2'])->willReturn([$product1Mock, $product2Mock]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'products' => [$product1Mock, $product2Mock],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->productsGridContentElementRenderer->render($contentConfigurationMock));
    }
}
