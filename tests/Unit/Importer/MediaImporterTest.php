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

namespace Tests\Sylius\CmsPlugin\Unit\Importer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Importer\MediaImporter;
use Sylius\CmsPlugin\Importer\MediaImporterInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterCollectionsResolverInterface;
use Sylius\CmsPlugin\Resolver\ResourceResolverInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class MediaImporterTest extends TestCase
{
    /** @var ResourceResolverInterface&MockObject */
    private MockObject $mediaResourceResolverMock;

    /** @var LocaleContextInterface&MockObject */
    private MockObject $localeContextMock;

    /** @var ImporterCollectionsResolverInterface&MockObject */
    private MockObject $importerCollectionsResolverMock;

    /** @var ValidatorInterface&MockObject */
    private MockObject $validatorMock;

    /** @var MediaRepositoryInterface&MockObject */
    private MockObject $mediaRepositoryMock;

    private MediaImporter $mediaImporter;

    protected function setUp(): void
    {
        $this->mediaResourceResolverMock = $this->createMock(ResourceResolverInterface::class);
        $this->localeContextMock = $this->createMock(LocaleContextInterface::class);
        $this->importerCollectionsResolverMock = $this->createMock(ImporterCollectionsResolverInterface::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->mediaRepositoryMock = $this->createMock(MediaRepositoryInterface::class);
        $this->mediaImporter = new MediaImporter($this->mediaResourceResolverMock, $this->localeContextMock, $this->importerCollectionsResolverMock, $this->validatorMock, $this->mediaRepositoryMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(MediaImporter::class, $this->mediaImporter);
        self::assertInstanceOf(MediaImporterInterface::class, $this->mediaImporter);
    }

    public function testImportsMedia(): void
    {
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        $row = ['name_pl' => 'name', 'content_pl' => 'content', 'alt_pl' => 'alt', 'code' => 'media_code'];
        $this->mediaResourceResolverMock->expects(self::once())->method('getResource')->with('media_code')->willReturn($mediaMock);
        $this->localeContextMock->expects(self::once())->method('getLocaleCode')->willReturn('en_US');
        $mediaMock->expects(self::once())->method('setCode')->with('media_code');
        $mediaMock->expects(self::once())->method('setType')->with(null);
        $mediaMock->expects(self::once())->method('setFallbackLocale')->with('en_US');
        $mediaMock->expects(self::once())->method('setCurrentLocale')->with('pl');
        $mediaMock->expects(self::once())->method('setName')->with('name');
        $mediaMock->expects(self::once())->method('setContent')->with('content');
        $mediaMock->expects(self::once())->method('setAlt')->with('alt');
        $this->importerCollectionsResolverMock->expects(self::once())->method('resolve')->with($mediaMock, null);
        $this->validatorMock->expects(self::once())->method('validate')->with($mediaMock, null, ['cms'])->willReturn(new ConstraintViolationList());
        $this->mediaRepositoryMock->expects(self::once())->method('add')->with($mediaMock);
        $this->mediaImporter->import($row);
    }

    public function testGetsResourceCode(): void
    {
        self::assertSame('media', $this->mediaImporter->getResourceCode());
    }
}
