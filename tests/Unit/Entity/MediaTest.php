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

namespace Tests\Sylius\CmsPlugin\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\Media;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class MediaTest extends TestCase
{
    private MediaInterface $media;

    protected function setUp(): void
    {
        $this->media = new Media();
    }

    public function testAllowsAccessViaProperties(): void
    {
        $this->media->setCode('file');
        self::assertSame('file', $this->media->getCode());

        $this->media->setName('Video');
        self::assertSame('Video', $this->media->getName());

        $this->media->setType('video');
        self::assertSame('video', $this->media->getType());

        $this->media->setPath('/media/video');
        self::assertSame('/media/video', $this->media->getPath());

        $file = new UploadedFile(__DIR__ . '/MediaTest.php', 'originalName');

        $this->media->setFile($file);
        self::assertSame($file, $this->media->getFile());

        $this->media->setMimeType('video/mp4');
        self::assertSame('video/mp4', $this->media->getMimeType());
    }

    public function testToggles(): void
    {
        $this->media->enable();
        self::assertTrue($this->media->isEnabled());

        $this->media->disable();
        self::assertFalse($this->media->isEnabled());
    }

    public function testAssociatesCollections(): void
    {
        /** @var CollectionInterface&MockObject $firstCollectionMock */
        $firstCollectionMock = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $secondCollectionMock */
        $secondCollectionMock = $this->createMock(CollectionInterface::class);

        $this->media->addCollection($firstCollectionMock);
        self::assertTrue($this->media->hasCollection($firstCollectionMock));
        self::assertFalse($this->media->hasCollection($secondCollectionMock));

        $this->media->removeCollection($firstCollectionMock);
        self::assertFalse($this->media->hasCollection($firstCollectionMock));
    }

    public function testAssociatesChannels(): void
    {
        /** @var ChannelInterface&MockObject $firstChannelMock */
        $firstChannelMock = $this->createMock(ChannelInterface::class);
        /** @var ChannelInterface&MockObject $secondChannelMock */
        $secondChannelMock = $this->createMock(ChannelInterface::class);

        $this->media->addChannel($firstChannelMock);
        self::assertTrue($this->media->hasChannel($firstChannelMock));
        self::assertFalse($this->media->hasChannel($secondChannelMock));

        $this->media->removeChannel($firstChannelMock);
        self::assertFalse($this->media->hasChannel($firstChannelMock));
    }
}
