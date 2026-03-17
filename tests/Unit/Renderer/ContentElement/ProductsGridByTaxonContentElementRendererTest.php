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
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridByTaxonContentElementType;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridByTaxonContentElementRenderer;
use Sylius\Component\Core\Model\ProductInterface;
use Twig\Environment;

final class ProductsGridByTaxonContentElementRendererTest extends TestCase
{
    /** @var ProductsProviderInterface&MockObject */
    private MockObject $productsProviderMock;

    private ProductsGridByTaxonContentElementRenderer $productsGridByTaxonContentElementRenderer;

    protected function setUp(): void
    {
        $this->productsProviderMock = $this->createMock(ProductsProviderInterface::class);
        $this->productsGridByTaxonContentElementRenderer = new ProductsGridByTaxonContentElementRenderer($this->productsProviderMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(ProductsGridByTaxonContentElementRenderer::class, $this->productsGridByTaxonContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->productsGridByTaxonContentElementRenderer);
    }

    public function testSupportsProductsGridByTaxonContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(ProductsGridByTaxonContentElementType::TYPE);
        self::assertTrue($this->productsGridByTaxonContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->productsGridByTaxonContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersProductsGridByTaxonContentElement(): void
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
        $this->productsGridByTaxonContentElementRenderer->setTemplate($template);
        $this->productsGridByTaxonContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'products_grid_by_taxon' => 'taxon_code',
        ]);
        $this->productsProviderMock->expects(self::once())->method('getProductsByTaxonCode')->with('taxon_code')->willReturn([$product1Mock, $product2Mock]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'products' => [$product1Mock, $product2Mock],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->productsGridByTaxonContentElementRenderer->render($contentConfigurationMock));
    }

    public function testReturnsEmptyStringWhenNoProductsFound(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $this->productsGridByTaxonContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'products_grid_by_taxon' => 'taxon_code',
        ]);
        $this->productsProviderMock->expects(self::once())->method('getProductsByTaxonCode')->with('taxon_code')->willReturn([]);
        $twigMock->expects(self::never())->method('render');
        self::assertSame('', $this->productsGridByTaxonContentElementRenderer->render($contentConfigurationMock));
    }
}
