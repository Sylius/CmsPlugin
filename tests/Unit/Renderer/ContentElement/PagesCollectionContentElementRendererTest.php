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

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\PagesCollectionContentElementType;
use Sylius\CmsPlugin\Renderer\ContentElement\AbstractContentElement;
use Sylius\CmsPlugin\Renderer\ContentElement\PagesCollectionContentElementRenderer;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface;
use Twig\Environment;

final class PagesCollectionContentElementRendererTest extends TestCase
{
    /** @var CollectionRepositoryInterface&MockObject */
    private MockObject $collectionRepositoryMock;

    private PagesCollectionContentElementRenderer $pagesCollectionContentElementRenderer;

    protected function setUp(): void
    {
        $this->collectionRepositoryMock = $this->createMock(CollectionRepositoryInterface::class);
        $this->pagesCollectionContentElementRenderer = new PagesCollectionContentElementRenderer($this->collectionRepositoryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(PagesCollectionContentElementRenderer::class, $this->pagesCollectionContentElementRenderer);
        self::assertInstanceOf(AbstractContentElement::class, $this->pagesCollectionContentElementRenderer);
    }

    public function testSupportsPagesCollectionContentElementType(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn(PagesCollectionContentElementType::TYPE);
        self::assertTrue($this->pagesCollectionContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testDoesNotSupportOtherContentElementTypes(): void
    {
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        $contentConfigurationMock->expects(self::once())->method('getType')->willReturn('other_type');
        self::assertFalse($this->pagesCollectionContentElementRenderer->supports($contentConfigurationMock));
    }

    public function testRendersPagesCollectionContentElement(): void
    {
        /** @var Environment&MockObject $twigMock */
        $twigMock = $this->createMock(Environment::class);
        /** @var ContentConfigurationInterface&MockObject $contentConfigurationMock */
        $contentConfigurationMock = $this->createMock(ContentConfigurationInterface::class);
        /** @var CollectionInterface&MockObject $collectionMock */
        $collectionMock = $this->createMock(CollectionInterface::class);
        $template = 'custom_template';
        $this->pagesCollectionContentElementRenderer->setTemplate($template);
        $this->pagesCollectionContentElementRenderer->setTwigEnvironment($twigMock);
        $contentConfigurationMock->expects(self::once())->method('getConfiguration')->willReturn([
            'pages_collection' => 'collection_code',
        ]);
        $this->collectionRepositoryMock->expects(self::once())->method('findOneBy')->with(['code' => 'collection_code'])->willReturn($collectionMock);
        $pagesCollection = new ArrayCollection(['page1', 'page2']);
        $collectionMock->expects(self::once())->method('getPages')->willReturn($pagesCollection);
        $twigMock->expects(self::once())->method('render')->with('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $template,
            'collection' => $pagesCollection,
        ])->willReturn('rendered_output');
        self::assertSame('rendered_output', $this->pagesCollectionContentElementRenderer->render($contentConfigurationMock));
    }
}
