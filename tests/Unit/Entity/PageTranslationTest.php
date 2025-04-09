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
use Sylius\CmsPlugin\Entity\PageTranslation;
use Sylius\CmsPlugin\Entity\PageTranslationInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

final class PageTranslationTest extends TestCase
{
    private PageTranslationInterface $pageTranslation;

    protected function setUp(): void
    {
        $this->pageTranslation = new PageTranslation();
    }

    public function testImplementsPageTranslationInterface(): void
    {
        self::assertInstanceOf(PageTranslationInterface::class, $this->pageTranslation);
        self::assertInstanceOf(TranslationInterface::class, $this->pageTranslation);
    }

    public function testAllowsAccessViaProperties(): void
    {
        $this->pageTranslation->setSlug('homepage');
        self::assertSame('homepage', $this->pageTranslation->getSlug());
        $this->pageTranslation->setMetaKeywords('homepage');
        self::assertSame('homepage', $this->pageTranslation->getMetaKeywords());
        $this->pageTranslation->setMetaDescription('Description');
        self::assertSame('Description', $this->pageTranslation->getMetaDescription());
        $this->pageTranslation->setTitle('title');
        self::assertSame('title', $this->pageTranslation->getTitle());
    }
}
