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

namespace Tests\Sylius\CmsPlugin\Unit\Resolver;

use BadFunctionCallException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Resolver\ResourceResolver;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ResourceResolverTest extends TestCase
{
    /** @var RepositoryInterface&MockObject */
    private MockObject $repositoryMock;

    /** @var FactoryInterface&MockObject */
    private MockObject $factoryMock;

    private ResourceResolver $resourceResolver;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(RepositoryInterface::class);
        $this->factoryMock = $this->createMock(FactoryInterface::class);
        $this->resourceResolver = new ResourceResolver($this->repositoryMock, $this->factoryMock, 'unique_column');
    }

    public function testReturnsExistingResourceFromRepository(): void
    {
        /** @var ResourceInterface&MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $identifier = 'resource_identifier';
        $this->repositoryMock->expects(self::once())->method('findOneBy')->with(['unique_column' => $identifier])->willReturn($resourceMock);
        self::assertSame($resourceMock, $this->resourceResolver->getResource($identifier));
    }

    public function testCreatesNewResourceUsingFactory(): void
    {
        /** @var ResourceInterface&MockObject $newResourceMock */
        $newResourceMock = $this->createMock(ResourceInterface::class);
        $identifier = 'resource_identifier';
        $factoryMethod = 'createNew';
        $this->repositoryMock->expects(self::once())->method('findOneBy')->with(['unique_column' => $identifier])->willReturn(null);
        $this->factoryMock->expects(self::once())->method('createNew')->willReturn($newResourceMock);
        self::assertSame($newResourceMock, $this->resourceResolver->getResource($identifier, $factoryMethod));
    }

    public function testThrowsExceptionWhenFactoryMethodNotCallable(): void
    {
        $identifier = 'resource_identifier';
        $factoryMethod = 'nonExistingMethod';
        $this->repositoryMock->expects(self::once())->method('findOneBy')->with(['unique_column' => $identifier])->willReturn(null);
        $this->expectException(BadFunctionCallException::class);
        $this->resourceResolver->getResource($identifier, $factoryMethod);
    }
}
