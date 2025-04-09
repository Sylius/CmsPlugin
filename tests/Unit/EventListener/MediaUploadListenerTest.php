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

namespace Tests\Sylius\CmsPlugin\Unit\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\EventListener\MediaUploadListener;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;

final class MediaUploadListenerTest extends TestCase
{
    /** @var MediaProviderResolverInterface&MockObject */
    private MockObject $mediaProviderResolverMock;

    private MediaUploadListener $mediaUploadListener;

    protected function setUp(): void
    {
        $this->mediaProviderResolverMock = $this->createMock(MediaProviderResolverInterface::class);
        $this->mediaUploadListener = new MediaUploadListener($this->mediaProviderResolverMock);
    }

    public function testDoesNotUploadIfNotMediaInstance(): void
    {
        /** @var ResourceControllerEvent&MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        $eventMock->expects(self::once())->method('getSubject')->willReturn(null);

        $this->mediaProviderResolverMock->expects(self::never())->method('resolveProvider');

        $this->expectException(\InvalidArgumentException::class);

        $this->mediaUploadListener->uploadMedia($eventMock);
    }

    public function testUploadsMedia(): void
    {
        /** @var ResourceControllerEvent&MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        /** @var ProviderInterface&MockObject $providerMock */
        $providerMock = $this->createMock(ProviderInterface::class);

        $eventMock->expects(self::once())->method('getSubject')->willReturn($mediaMock);
        $this->mediaProviderResolverMock->expects(self::once())->method('resolveProvider')->with($mediaMock)->willReturn($providerMock);

        $this->mediaUploadListener->uploadMedia($eventMock);
    }
}
