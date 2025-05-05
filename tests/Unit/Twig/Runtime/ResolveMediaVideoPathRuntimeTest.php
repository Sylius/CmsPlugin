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

namespace Tests\Sylius\CmsPlugin\Unit\Twig\Runtime;

use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Twig\Runtime\ResolveMediaVideoPathRuntime;

final class ResolveMediaVideoPathRuntimeTest extends TestCase
{
    private FilesystemOperator $videoStorage;

    private string $videosDir;

    private string $publicDir;

    private ResolveMediaVideoPathRuntime $resolver;

    protected function setUp(): void
    {
        $this->videoStorage = $this->createMock(FilesystemOperator::class);
        $this->videosDir = '/home/project/public/media/video';
        $this->publicDir = '/home/project/public';

        $this->resolver = new ResolveMediaVideoPathRuntime(
            $this->videoStorage,
            $this->videosDir,
            $this->publicDir,
        );
    }

    public function testResolvesRelativePathsToWebAccessiblePaths(): void
    {
        $this->videoStorage
            ->expects($this->once())
            ->method('has')
            ->with('example.mp4')
            ->willReturn(true)
        ;

        $result = $this->resolver->resolve('example.mp4');

        $this->assertEquals('/media/video/example.mp4', $result);
    }

    public function testHandlesPathsWithLeadingSlashes(): void
    {
        $this->videoStorage
            ->expects($this->once())
            ->method('has')
            ->with('example.mp4')
            ->willReturn(true)
        ;

        $result = $this->resolver->resolve('example.mp4');

        $this->assertEquals('/media/video/example.mp4', $result);
    }

    public function testReturnsUnchangedPathWhenFileDoesNotExist(): void
    {
        $this->videoStorage
            ->expects($this->once())
            ->method('has')
            ->with('missing.mp4')
            ->willReturn(false)
        ;

        $result = $this->resolver->resolve('missing.mp4');

        $this->assertEquals('missing.mp4', $result);
    }

    public function testHandlesCustomPublicDirectoryStructures(): void
    {
        $customResolver = new ResolveMediaVideoPathRuntime(
            $this->videoStorage,
            '/var/www/public_html/uploads/videos',
            '/var/www/public_html',
        );

        $this->videoStorage
            ->expects($this->once())
            ->method('has')
            ->with('movie.mp4')
            ->willReturn(true)
        ;

        $result = $customResolver->resolve('movie.mp4');

        $this->assertEquals('/uploads/videos/movie.mp4', $result);
    }

    public function testHandlesNestedPathsCorrectly(): void
    {
        $this->videoStorage
            ->expects($this->once())
            ->method('has')
            ->with('subdir/video.mp4')
            ->willReturn(true)
        ;

        $result = $this->resolver->resolve('subdir/video.mp4');

        $this->assertEquals('/media/video/subdir/video.mp4', $result);
    }
}
