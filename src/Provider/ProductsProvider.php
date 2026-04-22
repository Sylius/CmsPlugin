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

namespace Sylius\CmsPlugin\Provider;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

final readonly class ProductsProvider implements ProductsProviderInterface
{
    /**
     * @param EntityRepository&ProductRepositoryInterface<ProductInterface> $productRepository
     */
    public function __construct(
        private EntityRepository&ProductRepositoryInterface $productRepository,
        private ChannelContextInterface $channelContext,
    ) {
    }

    /** @param string[] $productCodes */
    public function getProductsByCodes(array $productCodes): array
    {
        return $this->productRepository->createQueryBuilder('p')
            ->innerJoin('p.channels', 'c')
            ->where('p.code IN (:codes)')
            ->andWhere('p.enabled = true')
            ->andWhere('c = :channel')
            ->setParameter('codes', $productCodes)
            ->setParameter('channel', $this->channelContext->getChannel())
            ->getQuery()
            ->getResult()
        ;
    }

    public function getProductsByTaxonCode(string $taxonCode): array
    {
        return $this->productRepository->createQueryBuilder('p')
            ->innerJoin('p.channels', 'c')
            ->innerJoin('p.productTaxons', 'pt')
            ->innerJoin('pt.taxon', 't')
            ->where('t.code = :taxonCode')
            ->andWhere('p.enabled = true')
            ->andWhere('c = :channel')
            ->setParameter('taxonCode', $taxonCode)
            ->setParameter('channel', $this->channelContext->getChannel())
            ->getQuery()
            ->getResult()
        ;
    }
}
