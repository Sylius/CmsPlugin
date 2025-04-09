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

namespace Tests\Sylius\CmsPlugin\Unit\Resolver\Importer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Assigner\LocalesAssignerInterface;
use Sylius\CmsPlugin\Entity\LocaleAwareInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterLocalesResolver;

final class ImporterLocalesResolverTest extends TestCase
{
    /** @var LocalesAssignerInterface&MockObject */
    private MockObject $localesAssignerMock;

    private ImporterLocalesResolver $importerLocalesResolver;

    protected function setUp(): void
    {
        $this->localesAssignerMock = $this->createMock(LocalesAssignerInterface::class);
        $this->importerLocalesResolver = new ImporterLocalesResolver($this->localesAssignerMock);
    }

    public function testResolvesLocalesForLocaleAwareEntity(): void
    {
        /** @var LocaleAwareInterface&MockObject $localesAwareMock */
        $localesAwareMock = $this->createMock(LocaleAwareInterface::class);
        $localesRow = 'en_US, fr_FR';
        $this->localesAssignerMock->expects(self::once())->method('assign')->with($localesAwareMock, ['en_US', 'fr_FR']);
        $this->importerLocalesResolver->resolve($localesAwareMock, $localesRow);
    }

    public function testDoesNotAssignLocalesWhenLocalesRowIsEmpty(): void
    {
        /** @var LocaleAwareInterface&MockObject $localesAwareMock */
        $localesAwareMock = $this->createMock(LocaleAwareInterface::class);
        $this->localesAssignerMock->expects(self::never())->method('assign')->with($localesAwareMock, []);
        $this->importerLocalesResolver->resolve($localesAwareMock, '');
    }
}
