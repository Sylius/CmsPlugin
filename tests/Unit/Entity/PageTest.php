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

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\Page;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class PageTest extends TestCase
{
    private PageInterface $page;

    protected function setUp(): void
    {
        $this->page = new Page();
    }

    public function testAllowsAccessViaProperties(): void
    {
        $this->page->setCode('homepage');
        self::assertSame('homepage', $this->page->getCode());
    }

    public function testToggles(): void
    {
        $this->page->enable();
        self::assertTrue($this->page->isEnabled());

        $this->page->disable();
        self::assertFalse($this->page->isEnabled());
    }

    public function testAssociatesCollections(): void
    {
        /** @var CollectionInterface&MockObject $firstCollectionMock */
        $firstCollectionMock = $this->createMock(CollectionInterface::class);
        /** @var CollectionInterface&MockObject $secondCollectionMock */
        $secondCollectionMock = $this->createMock(CollectionInterface::class);
        $this->page->addCollection($firstCollectionMock);
        self::assertTrue($this->page->hasCollection($firstCollectionMock));
        self::assertFalse($this->page->hasCollection($secondCollectionMock));
        $this->page->removeCollection($firstCollectionMock);
        self::assertFalse($this->page->hasCollection($firstCollectionMock));
    }

    public function testAssociatesChannels(): void
    {
        /** @var ChannelInterface&MockObject $firstChannelMock */
        $firstChannelMock = $this->createMock(ChannelInterface::class);
        /** @var ChannelInterface&MockObject $secondChannelMock */
        $secondChannelMock = $this->createMock(ChannelInterface::class);
        $this->page->addChannel($firstChannelMock);
        self::assertTrue($this->page->hasChannel($firstChannelMock));
        self::assertFalse($this->page->hasChannel($secondChannelMock));
        $this->page->removeChannel($firstChannelMock);
        self::assertFalse($this->page->hasChannel($firstChannelMock));
    }

    public function testTimestampable(): void
    {
        $dateTime = new DateTime();

        $this->page->setCreatedAt($dateTime);
        self::assertSame($dateTime, $this->page->getCreatedAt());

        $this->page->setUpdatedAt($dateTime);
        self::assertSame($dateTime, $this->page->getUpdatedAt());
    }
}
