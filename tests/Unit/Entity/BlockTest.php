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
use Sylius\CmsPlugin\Entity\Block;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class BlockTest extends TestCase
{
    private BlockInterface $block;

    protected function setUp(): void
    {
        $this->block = new Block();
    }

    public function testToggles(): void
    {
        $this->block->enable();
        self::assertTrue($this->block->isEnabled());

        $this->block->disable();
        self::assertFalse($this->block->isEnabled());
    }

    public function testAssociatesCollections(): void
    {
        /** @var CollectionInterface&MockObject $firstCollectionMock */
        $firstCollectionMock = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $secondCollectionMock */
        $secondCollectionMock = $this->createMock(CollectionInterface::class);

        $this->block->addCollection($firstCollectionMock);
        self::assertTrue($this->block->hasCollection($firstCollectionMock));
        self::assertFalse($this->block->hasCollection($secondCollectionMock));

        $this->block->removeCollection($firstCollectionMock);
        self::assertFalse($this->block->hasCollection($firstCollectionMock));
    }

    public function testAssociatesChannels(): void
    {
        /** @var ChannelInterface&MockObject $firstChannelMock */
        $firstChannelMock = $this->createMock(ChannelInterface::class);
        /** @var ChannelInterface&MockObject $secondChannelMock */
        $secondChannelMock = $this->createMock(ChannelInterface::class);

        $this->block->addChannel($firstChannelMock);
        self::assertTrue($this->block->hasChannel($firstChannelMock));
        self::assertFalse($this->block->hasChannel($secondChannelMock));

        $this->block->removeChannel($firstChannelMock);
        self::assertFalse($this->block->hasChannel($firstChannelMock));
    }
}
