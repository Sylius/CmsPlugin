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

namespace Tests\Sylius\CmsPlugin\Unit\Assigner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Assigner\LocalesAssigner;
use Sylius\CmsPlugin\Entity\LocaleAwareInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class LocalesAssignerTest extends TestCase
{
    /** @var MockObject&RepositoryInterface<LocaleInterface> */
    private MockObject $localeRepository;

    private LocalesAssigner $localesAssigner;

    protected function setUp(): void
    {
        $this->localeRepository = $this->createMock(RepositoryInterface::class);

        $this->localesAssigner = new LocalesAssigner($this->localeRepository);
    }

    public function testImplementsLocalesAssignerInterface()
    {
        self::assertInstanceOf(LocalesAssigner::class, $this->localesAssigner);
    }

    public function testAssignsLocales(): void
    {
        /** @var LocaleAwareInterface&MockObject $localesAware */
        $localesAware = $this->createMock(LocaleAwareInterface::class);
        /** @var LocaleInterface&MockObject $locale1 */
        $locale1 = $this->createMock(LocaleInterface::class);
        /** @var LocaleInterface&MockObject $locale2 */
        $locale2 = $this->createMock(LocaleInterface::class);

        $this->localeRepository
            ->expects(self::once())
            ->method('findBy')
            ->with(['code' => ['en_US', 'fr_FR']])
            ->willReturn([$locale1, $locale2])
        ;

        $localesAware->expects(self::exactly(2))->method('addLocale');

        $this->localesAssigner->assign($localesAware, ['en_US', 'fr_FR']);
    }
}
