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
use Sylius\CmsPlugin\Form\Type\ContentElements\ProductsGridContentElementType;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final class ProductsGridContentElementRenderer extends AbstractContentElement
{
    /** @param ProductRepositoryInterface<ProductInterface>|ProductsProviderInterface $productsRepository */
    public function __construct(
        private readonly ProductRepositoryInterface|ProductsProviderInterface $productsRepository,
        private readonly ChannelContextInterface $channelContext,
    ) {
        if ($this->productsRepository instanceof ProductRepositoryInterface) {
            trigger_deprecation(
                'sylius/cms-plugin',
                '1.2',
                'Passing "%s" as the first argument of "%s" constructor is deprecated. Pass "%s" instead. This will become mandatory in 2.0',
                ProductRepositoryInterface::class,
                self::class,
                ProductsProviderInterface::class,
            );
        }
    }

    public function supports(ContentConfigurationInterface $contentConfiguration): bool
    {
        return ProductsGridContentElementType::TYPE === $contentConfiguration->getType();
    }

    public function render(ContentConfigurationInterface $contentConfiguration): string
    {
        $configuration = $contentConfiguration->getConfiguration();
        $productsCodes = $configuration['products_grid']['products'];
        $channel = $this->channelContext->getChannel();

        if ($this->productsRepository instanceof ProductsProviderInterface) {
            $products = $this->productsRepository->getProductsByCodes($productsCodes, $channel);
        } else {
            $products = $this->productsRepository->findBy(['code' => $productsCodes]);
        }

        return $this->twig->render('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $this->template,
            'products' => $products,
        ]);
    }
}
