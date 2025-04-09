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

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Importer\PageImporter;
use Sylius\CmsPlugin\Importer\PageImporterInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterChannelsResolverInterface;
use Sylius\CmsPlugin\Resolver\Importer\ImporterCollectionsResolverInterface;
use Sylius\CmsPlugin\Resolver\ResourceResolverInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PageImporterTest extends TestCase
{
    /** @var ResourceResolverInterface&MockObject */
    private MockObject $pageResourceResolverMock;

    /** @var LocaleContextInterface&MockObject */
    private MockObject $localeContextMock;

    /** @var ImporterCollectionsResolverInterface&MockObject */
    private MockObject $importerCollectionsResolverMock;

    /** @var ImporterChannelsResolverInterface&MockObject */
    private MockObject $importerChannelsResolverMock;

    /** @var ValidatorInterface&MockObject */
    private MockObject $validatorMock;

    /** @var EntityManagerInterface&MockObject */
    private MockObject $entityManagerMock;

    private PageImporter $pageImporter;

    protected function setUp(): void
    {
        $this->pageResourceResolverMock = $this->createMock(ResourceResolverInterface::class);
        $this->localeContextMock = $this->createMock(LocaleContextInterface::class);
        $this->importerCollectionsResolverMock = $this->createMock(ImporterCollectionsResolverInterface::class);
        $this->importerChannelsResolverMock = $this->createMock(ImporterChannelsResolverInterface::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->pageImporter = new PageImporter($this->pageResourceResolverMock, $this->localeContextMock, $this->importerCollectionsResolverMock, $this->importerChannelsResolverMock, $this->validatorMock, $this->entityManagerMock);
    }

    public function testInitializable(): void
    {
        self::assertInstanceOf(PageImporter::class, $this->pageImporter);
        self::assertInstanceOf(PageImporterInterface::class, $this->pageImporter);
    }

    public function testImportsPageNoUrl(): void
    {
        /** @var PageInterface&MockObject $pageMock */
        $pageMock = $this->createMock(PageInterface::class);
        $row = [
            'code' => 'page_code',
            'name' => 'page_name',
            'enabled' => '1',
            'slug_pl' => 'slug',
            'meta_title_pl' => 'metatitle',
            'meta_keywords_pl' => 'metakeywords',
            'meta_description_pl' => 'metadescription',
            'collections' => 'collections',
            'channels' => 'channels',
        ];
        $this->pageResourceResolverMock->expects(self::once())->method('getResource')->with('page_code')->willReturn($pageMock);
        $this->localeContextMock->expects(self::once())->method('getLocaleCode')->willReturn('en_US');
        $pageMock->expects(self::once())->method('setCode')->with('page_code');
        $pageMock->expects(self::once())->method('setName')->with('page_name');
        $pageMock->expects(self::once())->method('setEnabled')->with(true);
        $pageMock->expects(self::once())->method('setFallbackLocale')->with('en_US');
        $pageMock->expects(self::once())->method('setCurrentLocale')->with('pl');
        $pageMock->expects(self::once())->method('setSlug')->with('slug');
        $pageMock->expects(self::once())->method('setTitle')->with('metatitle');
        $pageMock->expects(self::once())->method('setMetaKeywords')->with('metakeywords');
        $pageMock->expects(self::once())->method('setMetaDescription')->with('metadescription');
        $this->importerCollectionsResolverMock->expects(self::once())->method('resolve')->with($pageMock, 'collections');
        $this->importerChannelsResolverMock->expects(self::once())->method('resolve')->with($pageMock, 'channels');
        $this->validatorMock->expects(self::once())->method('validate')->with($pageMock, null, ['cms'])->willReturn(new ConstraintViolationList());
        $this->entityManagerMock->expects(self::once())->method('persist')->with($pageMock);
        $this->entityManagerMock->expects(self::once())->method('flush');
        $this->pageImporter->import($row);
    }

    public function testGetsResourceCode(): void
    {
        self::assertSame('page', $this->pageImporter->getResourceCode());
    }
}
