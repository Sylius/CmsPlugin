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

namespace Sylius\CmsPlugin\Renderer\ContentElement;

use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsCarouselByTaxonContentElementType;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

final class ProductsCarouselByTaxonContentElementRenderer extends AbstractContentElement
{
    /**
     * @param ProductRepositoryInterface<ProductInterface>|ProductsProviderInterface $productsRepository
     * @param TaxonRepositoryInterface<TaxonInterface>|null $taxonRepository
     */
    public function __construct(
        private ProductRepositoryInterface|ProductsProviderInterface $productsRepository,
        private readonly ChannelContextInterface $channelContext,
        private ?TaxonRepositoryInterface $taxonRepository = null,
    ) {
        if ($this->productsRepository instanceof ProductRepositoryInterface) {
            if (null === $this->taxonRepository) {
                throw new \InvalidArgumentException(sprintf(
                    'The second argument of "%s" constructor must be an instance of "%s" when passing "%s" as the first argument.',
                    self::class,
                    TaxonRepositoryInterface::class,
                    ProductRepositoryInterface::class,
                ));
            }

            trigger_deprecation(
                'sylius/cms-plugin',
                '1.1.5',
                'Passing "%s" as the first argument of "%s" constructor is deprecated. Pass "%s" instead.',
                ProductRepositoryInterface::class,
                self::class,
                ProductsProviderInterface::class,
            );
        }
    }

    public function supports(ContentConfigurationInterface $contentConfiguration): bool
    {
        return ProductsCarouselByTaxonContentElementType::TYPE === $contentConfiguration->getType();
    }

    public function render(ContentConfigurationInterface $contentConfiguration): string
    {
        $taxonCode = $contentConfiguration->getConfiguration()['products_carousel_by_taxon'];
        $channel = $this->channelContext->getChannel();

        if ($this->productsRepository instanceof ProductsProviderInterface) {
            $products = $this->productsRepository->getProductsByTaxonCode($taxonCode, $channel);
        } else {
            Assert::notNull($this->taxonRepository);
            /** @var TaxonInterface|null $taxon */
            $taxon = $this->taxonRepository->findOneBy(['code' => $taxonCode]);
            if (null === $taxon) {
                return '';
            }

            $products = $this->productsRepository->findByTaxon($taxon);
        }

        if ([] === $products) {
            return '';
        }

        return $this->twig->render('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $this->template,
            'products' => $products,
        ]);
    }
}
