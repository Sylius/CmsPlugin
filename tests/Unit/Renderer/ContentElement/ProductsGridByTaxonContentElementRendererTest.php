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
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\ProductsGridByTaxonContentElementRenderer;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Twig\Environment;

final class ProductsGridByTaxonContentElementRendererTest extends TestCase
{
    /** @var ProductRepositoryInterface&MockObject */
    private MockObject $productRepositoryMock;

    /** @var TaxonRepositoryInterface&MockObject */
    private MockObject $taxonRepositoryMock;

    private ProductsGridByTaxonContentElementRenderer $productsGridByTaxonContentElementRenderer;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->productsGridByTaxonContentElementRenderer = new ProductsGridByTaxonContentElementRenderer($this->productRepositoryMock, $this->taxonRepositoryMock);
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
        /** @var TaxonInterface&MockObject $taxonMock */
        $taxonMock = $this->createMock(TaxonInterface::class);
        /** @var Product&MockObject $product1Mock */
        $product1Mock = $this->createMock(Product::class);
        /** @var Product&MockObject $product2Mock */
        $product2Mock = $this->createMock(Product::class);
        $template = 'custom_template';
        $this->productsGridByTaxonContentElementRenderer->setTemplate($template);
        $this->productsGridByTaxonContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'products_grid_by_taxon' => 'taxon_code',
        ]);
        $this->taxonRepositoryMock->expects(self::once())->method('findOneBy')->with(['code' => 'taxon_code'])->willReturn($taxonMock);
        $this->productRepositoryMock->expects(self::once())->method('findByTaxon')->with($taxonMock)->willReturn([$product1Mock, $product2Mock]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'products' => [$product1Mock, $product2Mock],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->productsGridByTaxonContentElementRenderer->render($contentConfigurationMock));
    }
}
