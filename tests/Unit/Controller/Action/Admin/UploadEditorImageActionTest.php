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

namespace Tests\Sylius\CmsPlugin\Unit\Controller\Action\Admin;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Controller\Action\Admin\UploadEditorImageAction;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\MediaProvider\ProviderInterface;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\CmsPlugin\Resolver\MediaProviderResolverInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

final class UploadEditorImageActionTest extends TestCase
{
    /** @var MediaProviderResolverInterface&MockObject */
    private MockObject $mediaProviderResolverMock;

    /** @var MediaRepositoryInterface&MockObject */
    private MockObject $mediaRepositoryMock;

    /** @var FactoryInterface&MockObject */
    private MockObject $mediaFactoryMock;

    private UploadEditorImageAction $uploadEditorImageAction;

    protected function setUp(): void
    {
        $this->mediaProviderResolverMock = $this->createMock(MediaProviderResolverInterface::class);
        $this->mediaRepositoryMock = $this->createMock(MediaRepositoryInterface::class);
        $this->mediaFactoryMock = $this->createMock(FactoryInterface::class);
        $this->uploadEditorImageAction = new UploadEditorImageAction($this->mediaProviderResolverMock, $this->mediaRepositoryMock, $this->mediaFactoryMock);
    }

    public function testUploadsMedia(): void
    {
        /** @var Request&MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        /** @var FileBag&MockObject $fileBagMock */
        $fileBagMock = $this->createMock(FileBag::class);
        /** @var ProviderInterface&MockObject $providerMock */
        $providerMock = $this->createMock(ProviderInterface::class);
        $uploadedFile = new UploadedFile(__DIR__ . '/../../../../Behat/Resources/images/aston_martin_db_11.jpg', 'aston_martin_db_11.jpg');
        $requestMock->files = $fileBagMock;
        $fileBagMock->expects(self::once())->method('get')->with('upload')->willReturn($uploadedFile);
        $this->mediaFactoryMock->expects(self::once())->method('createNew')->willReturn($mediaMock);
        $this->mediaProviderResolverMock->expects(self::once())->method('resolveProvider')->with($mediaMock)->willReturn($providerMock);
        $this->mediaRepositoryMock->expects(self::once())->method('findBy')->with(['code' => 'aston_martin_db_11'])->willReturn([]);
        $this->mediaRepositoryMock->expects(self::once())->method('add')->with($mediaMock);

        $this->uploadEditorImageAction->__invoke($requestMock);
    }
}
