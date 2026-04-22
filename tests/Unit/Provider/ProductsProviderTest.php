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

namespace Tests\Sylius\CmsPlugin\Unit\Provider;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\CmsPlugin\Provider\ProductsProvider;
use Sylius\CmsPlugin\Provider\ProductsProviderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductsProviderTest extends TestCase
{
    /** @var ProductRepository&MockObject */
    private MockObject $productRepositoryMock;

    /** @var ChannelContextInterface&MockObject */
    private MockObject $channelContextMock;

    private ProductsProvider $productsProvider;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->channelContextMock = $this->createMock(ChannelContextInterface::class);
        $this->productsProvider = new ProductsProvider(
            $this->productRepositoryMock,
            $this->channelContextMock,
        );
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(ProductsProvider::class, $this->productsProvider);
        self::assertInstanceOf(ProductsProviderInterface::class, $this->productsProvider);
    }

    public function testGetProductsByCodesQueriesDatabase(): void
    {
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        /** @var ProductInterface&MockObject $product1Mock */
        $product1Mock = $this->createMock(ProductInterface::class);
        /** @var ProductInterface&MockObject $product2Mock */
        $product2Mock = $this->createMock(ProductInterface::class);
        $queryBuilderMock = $this->createQueryBuilderMock([$product1Mock, $product2Mock]);

        $this->channelContextMock->method('getChannel')->willReturn($channelMock);
        $this->productRepositoryMock->expects(self::once())->method('createQueryBuilder')->willReturn($queryBuilderMock);

        self::assertSame([$product1Mock, $product2Mock], $this->productsProvider->getProductsByCodes(['code1', 'code2']));
    }

    public function testGetProductsByTaxonCodeQueriesDatabase(): void
    {
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        /** @var ProductInterface&MockObject $product1Mock */
        $product1Mock = $this->createMock(ProductInterface::class);
        $queryBuilderMock = $this->createQueryBuilderMock([$product1Mock]);

        $this->channelContextMock->method('getChannel')->willReturn($channelMock);
        $this->productRepositoryMock->expects(self::once())->method('createQueryBuilder')->willReturn($queryBuilderMock);

        self::assertSame([$product1Mock], $this->productsProvider->getProductsByTaxonCode('taxon_code'));
    }

    public function testGetProductsByCodesReturnsEmptyArrayWhenNoneFound(): void
    {
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        $queryBuilderMock = $this->createQueryBuilderMock([]);

        $this->channelContextMock->method('getChannel')->willReturn($channelMock);
        $this->productRepositoryMock->method('createQueryBuilder')->willReturn($queryBuilderMock);

        self::assertSame([], $this->productsProvider->getProductsByCodes(['unknown']));
    }

    public function testGetProductsByTaxonCodeReturnsEmptyArrayWhenNoneFound(): void
    {
        /** @var ChannelInterface&MockObject $channelMock */
        $channelMock = $this->createMock(ChannelInterface::class);
        $queryBuilderMock = $this->createQueryBuilderMock([]);

        $this->channelContextMock->method('getChannel')->willReturn($channelMock);
        $this->productRepositoryMock->method('createQueryBuilder')->willReturn($queryBuilderMock);

        self::assertSame([], $this->productsProvider->getProductsByTaxonCode('unknown_taxon'));
    }

    /** @param ProductInterface[] $result */
    private function createQueryBuilderMock(array $result): MockObject&QueryBuilder
    {
        $queryMock = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();
        $queryMock->method('getResult')->willReturn($result);

        $queryBuilderMock = $this->createMock(QueryBuilder::class);
        $queryBuilderMock->method('innerJoin')->willReturnSelf();
        $queryBuilderMock->method('where')->willReturnSelf();
        $queryBuilderMock->method('andWhere')->willReturnSelf();
        $queryBuilderMock->method('setParameter')->willReturnSelf();
        $queryBuilderMock->method('getQuery')->willReturn($queryMock);

        return $queryBuilderMock;
    }
}
