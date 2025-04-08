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

use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaTranslation;
use Sylius\CmsPlugin\Entity\MediaTranslationInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

final class MediaTranslationTest extends TestCase
{
    private MediaTranslationInterface $mediaTranslation;

    protected function setUp(): void
    {
        $this->mediaTranslation = new MediaTranslation();
    }

    public function testImplementsMediaTranslationInterface(): void
    {
        self::assertInstanceOf(MediaTranslationInterface::class, $this->mediaTranslation);
        self::assertInstanceOf(TranslationInterface::class, $this->mediaTranslation);
    }

    public function testAllowsAccessViaProperties(): void
    {
        $this->mediaTranslation->setContent('Lorem ipsum');
        self::assertSame('Lorem ipsum', $this->mediaTranslation->getContent());

        $this->mediaTranslation->setAlt('video');
        self::assertSame('video', $this->mediaTranslation->getAlt());

        $this->mediaTranslation->setLink('https://github.com/Netimage/SyliusCmsPlugin');
        self::assertSame('https://github.com/Netimage/SyliusCmsPlugin', $this->mediaTranslation->getLink());
    }
}
