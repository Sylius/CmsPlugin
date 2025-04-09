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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\CmsPlugin\Resolver\MediaProviderResolver;
use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Webmozart\Assert\InvalidArgumentException;

final class MediaProviderResolverTest extends TestCase
{
    /** @var ServiceRegistryInterface&MockObject */
    private MockObject $providerRegistryMock;

    private MediaProviderResolver $mediaProviderResolver;

    protected function setUp(): void
    {
        $this->providerRegistryMock = $this->createMock(ServiceRegistryInterface::class);

        $this->mediaProviderResolver = new MediaProviderResolver($this->providerRegistryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(MediaProviderResolver::class, $this->mediaProviderResolver);
        self::assertInstanceOf(MediaProviderResolverInterface::class, $this->mediaProviderResolver);
    }

    public function testResolvesProviderForMedia(): void
    {
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        /** @var ProviderInterface&MockObject $providerMock */
        $providerMock = $this->createMock(ProviderInterface::class);
        $mediaType = 'image';
        $mediaMock->expects(self::once())->method('getType')->willReturn($mediaType);

        $this->providerRegistryMock->expects(self::once())->method('get')->with($mediaType)->willReturn($providerMock);

        self::assertSame($providerMock, $this->mediaProviderResolver->resolveProvider($mediaMock));
    }

    public function testThrowsExceptionWhenMediaTypeIsNull(): void
    {
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        $mediaMock->expects(self::once())->method('getType')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);

        $this->mediaProviderResolver->resolveProvider($mediaMock);
    }
}
