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

namespace Tests\Sylius\CmsPlugin\Unit\MediaProvider;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\MediaProvider\GenericProvider;
use Sylius\CmsPlugin\Uploader\MediaUploaderInterface;
use Twig\Environment;

final class GenericProviderTest extends TestCase
{
    /** @var MediaUploaderInterface&MockObject */
    private MockObject $uploaderMock;

    /** @var Environment&MockObject */
    private MockObject $twigEngineMock;

    private GenericProvider $genericProvider;

    protected function setUp(): void
    {
        $this->uploaderMock = $this->createMock(MediaUploaderInterface::class);
        $this->twigEngineMock = $this->createMock(Environment::class);
        $this->genericProvider = new GenericProvider($this->uploaderMock, $this->twigEngineMock, '@Template', '/media/');
    }

    public function testRenders(): void
    {
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        $this->twigEngineMock->expects(self::once())->method('render')->with('@Template', ['media' => $mediaMock, 'config' => []])->willReturn('content');
        self::assertSame('content', $this->genericProvider->render($mediaMock, '@Template', ['config' => []]));
    }

    public function testUploads(): void
    {
        /** @var MediaInterface&MockObject $mediaMock */
        $mediaMock = $this->createMock(MediaInterface::class);
        $this->uploaderMock->expects(self::never())->method('upload')->with($mediaMock, '/media/');
    }
}
