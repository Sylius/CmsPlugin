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

namespace Sylius\CmsPlugin\Uploader;

use League\Flysystem\FilesystemException;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\Component\Core\Filesystem\Adapter\FilesystemAdapterInterface;
use Sylius\Component\Core\Filesystem\Exception\FileNotFoundException;
use Webmozart\Assert\Assert;

final class MediaUploader implements MediaUploaderInterface
{
    public function __construct(private FilesystemAdapterInterface $filesystem)
    {
    }

    public function upload(MediaInterface $media): void
    {
        if (!$media->hasFile()) {
            return;
        }

        $file = $media->getFile();
        Assert::notNull($file, sprintf('File for media identified by id: "%s" is null', $media->getId()));

        try {
            if (null !== $media->getPath() && $this->filesystem->has($media->getPath())) {
                $this->filesystem->delete($media->getPath());
            }

            $originalName = null;
            if ($media->getSaveWithOriginalName()) {
                $originalName = $file->getClientOriginalName();
            }

            $extension = $file->guessExtension();
            do {
                $hash = bin2hex(random_bytes(16));
                $path = $this->expandPath($hash . '.' . $extension, $originalName);
            } while ($this->filesystem->has($path));

            $media->setPath(\DIRECTORY_SEPARATOR . $path);
            $media->setMimeType($file->getMimeType());

            $file = $media->getFile();
            Assert::notNull($file, sprintf('File for media identified by id: "%s" is null', $media->getId()));

            $mimeType = $media->getMimeType();
            if (null !== $mimeType && str_starts_with($mimeType, 'image')) {
                $sizes = getimagesize($file->getPathname());
                if (false !== $sizes) {
                    [$width, $height] = $sizes;
                    $media->setWidth($width);
                    $media->setHeight($height);
                }
            }

            $mediaPath = $media->getPath();
            $fileContents = file_get_contents($file->getPathname());

            Assert::notNull($mediaPath, sprintf('Media path for media identified by id: "%s" is null', $media->getId()));
            Assert::notFalse($fileContents, sprintf('File contents for file identified by id: "%s" is false', $file->getPath()));

            $this->filesystem->write($mediaPath, $fileContents);
        } catch (FilesystemException $exception) {
            throw new \RuntimeException(sprintf('Could not upload file: %s', $exception->getMessage()));
        }
    }

    public function remove(string $path): bool
    {
        try {
            $this->filesystem->delete($path);
        } catch (FileNotFoundException) {
            return false;
        }

        return true;
    }

    private function expandPath(
        string $path,
        ?string $originalName = null,
    ): string {
        return str_replace('/', \DIRECTORY_SEPARATOR, sprintf(
            '%s/%s/%s',
            substr($path, 0, 2),
            substr($path, 2, 2),
            $originalName ?? substr($path, 4),
        ));
    }
}
