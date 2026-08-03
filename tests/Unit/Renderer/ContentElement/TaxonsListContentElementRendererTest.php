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
use Sylius\CmsPlugin\Form\Type\ContentElements\TaxonsListContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\TaxonsListContentElementRenderer;
use Sylius\Component\Core\Model\Taxon;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Twig\Environment;

final class TaxonsListContentElementRendererTest extends TestCase
{
    /** @var TaxonRepositoryInterface&MockObject */
    private MockObject $taxonRepositoryMock;

    private TaxonsListContentElementRenderer $taxonsListContentElementRenderer;

    protected function setUp(): void
    {
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->taxonsListContentElementRenderer = new TaxonsListContentElementRenderer($this->taxonRepositoryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(TaxonsListContentElementRenderer::class, $this->taxonsListContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->taxonsListContentElementRenderer);
    }

    public function testSupportsTaxonsListContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(TaxonsListContentElementType::TYPE);
        self::assertTrue($this->taxonsListContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->taxonsListContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersTaxonsListInTheConfiguredOrder(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var Taxon&MockObject $taxon1Mock */
        $taxon1Mock = $this->createMock(Taxon::class);
        $taxon1Mock->method('getCode')->willReturn('code1');
        $taxon1Mock->method('isEnabled')->willReturn(true);
        /** @var Taxon&MockObject $taxon2Mock */
        $taxon2Mock = $this->createMock(Taxon::class);
        $taxon2Mock->method('getCode')->willReturn('code2');
        $taxon2Mock->method('isEnabled')->willReturn(true);
        $template = 'custom_template';
        $this->taxonsListContentElementRenderer->setTemplate($template);
        $this->taxonsListContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'taxons_list' => ['taxons' => ['code2', 'code1']],
        ]);
        $this->taxonRepositoryMock->expects(self::once())->method('findBy')->with(['code' => ['code2', 'code1']])->willReturn([$taxon1Mock, $taxon2Mock]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'taxons' => [$taxon2Mock, $taxon1Mock],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->taxonsListContentElementRenderer->render($contentConfigurationMock));
    }

    public function testSkipsDisabledTaxons(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var Taxon&MockObject $enabledTaxonMock */
        $enabledTaxonMock = $this->createMock(Taxon::class);
        $enabledTaxonMock->method('getCode')->willReturn('code1');
        $enabledTaxonMock->method('isEnabled')->willReturn(true);
        /** @var Taxon&MockObject $disabledTaxonMock */
        $disabledTaxonMock = $this->createMock(Taxon::class);
        $disabledTaxonMock->method('getCode')->willReturn('code2');
        $disabledTaxonMock->method('isEnabled')->willReturn(false);
        $template = 'custom_template';
        $this->taxonsListContentElementRenderer->setTemplate($template);
        $this->taxonsListContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'taxons_list' => ['taxons' => ['code1', 'code2']],
        ]);
        $this->taxonRepositoryMock->expects(self::once())->method('findBy')->with(['code' => ['code1', 'code2']])->willReturn([$enabledTaxonMock, $disabledTaxonMock]);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'taxons' => [$enabledTaxonMock],
        ])->willReturn('rendered template');
        self::assertSame('rendered template', $this->taxonsListContentElementRenderer->render($contentConfigurationMock));
    }
}
