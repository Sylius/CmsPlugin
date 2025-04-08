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

namespace Tests\Sylius\CmsPlugin\Unit\Twig\Runtime;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;
use Sylius\CmsPlugin\Resolver\MediaResourceResolverInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderMediaRuntime;

final class RenderMediaRuntimeTest extends TestCase
{
    /** @var MediaProviderResolverInterface&MockObject */
    private MockObject $mediaProviderResolverMock;

    /** @var MediaResourceResolverInterface&MockObject */
    private MockObject $mediaResourceResolverMock;

    private RenderMediaRuntime $renderMediaRuntime;

    protected function setUp(): void
    {
        $this->mediaProviderResolverMock = $this->createMock(MediaProviderResolverInterface::class);
        $this->mediaResourceResolverMock = $this->createMock(MediaResourceResolverInterface::class);
        $this->renderMediaRuntime = new RenderMediaRuntime($this->mediaProviderResolverMock, $this->mediaResourceResolverMock);
    }

    public function testRendersMedia(): void
    {
        /** @var ProviderInterface&MockObject $providerMock */
        $providerMock = $this->createMock(ProviderInterface::class);
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        $this->mediaResourceResolverMock->expects(self::once())->method('findOrLog')->with('sylius_cms')->willReturn($mediaMock);
        $providerMock->expects(self::once())->method('render')->with($mediaMock, null)->willReturn('content');
        $this->mediaProviderResolverMock->expects(self::once())->method('resolveProvider')->with($mediaMock)->willReturn($providerMock);
        self::assertSame('content', $this->renderMediaRuntime->renderMedia('sylius_cms'));
    }
}
