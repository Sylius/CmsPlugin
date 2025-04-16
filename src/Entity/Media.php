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

namespace Sylius\CmsPlugin\Entity;

use Sylius\CmsPlugin\Entity\Trait\ChannelsAwareTrait;
use Sylius\CmsPlugin\Entity\Trait\CollectibleTrait;
use Sylius\CmsPlugin\Entity\Trait\PagesCollectionTrait;
use Sylius\CmsPlugin\MediaProvider\FilenameHelper;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Resource\Model\TranslationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Media implements MediaInterface
{
    use ToggleableTrait;
    use CollectibleTrait;
    use ChannelsAwareTrait;
    use PagesCollectionTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    protected ?int $id = null;

    protected ?string $type = null;

    protected ?string $code = null;

    protected ?string $name = null;

    protected ?string $path = null;

    protected ?UploadedFile $file = null;

    protected ?string $mimeType = null;

    protected string $originalPath;

    protected ?int $width = null;

    protected ?int $height = null;

    protected bool $saveWithOriginalName = false;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->initializeCollectionsCollection();
        $this->initializeChannelsCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function hasFile(): bool
    {
        return null !== $this->file;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getDownloadName(): string
    {
        return FilenameHelper::removeSlashes($this->getName() ?? $this->getCode() ?? self::DEFAULT_DOWNLOAD_NAME);
    }

    public function getContent(): ?string
    {
        /** @var MediaTranslationInterface $mediaTranslationInterface */
        $mediaTranslationInterface = $this->getMediaTranslation();

        return $mediaTranslationInterface->getContent();
    }

    public function setContent(?string $content): void
    {
        /** @var MediaTranslationInterface $mediaTranslationInterface */
        $mediaTranslationInterface = $this->getMediaTranslation();
        $mediaTranslationInterface->setContent($content);
    }

    public function getAlt(): ?string
    {
        /** @var MediaTranslationInterface $mediaTranslationInterface */
        $mediaTranslationInterface = $this->getMediaTranslation();

        return $mediaTranslationInterface->getAlt();
    }

    public function setAlt(?string $alt): void
    {
        /** @var MediaTranslationInterface $mediaTranslationInterface */
        $mediaTranslationInterface = $this->getMediaTranslation();
        $mediaTranslationInterface->setAlt($alt);
    }

    public function getLink(): ?string
    {
        /** @var MediaTranslationInterface $mediaTranslationInterface */
        $mediaTranslationInterface = $this->getMediaTranslation();

        return $mediaTranslationInterface->getLink();
    }

    public function setLink(?string $link): void
    {
        /** @var MediaTranslationInterface $mediaTranslationInterface */
        $mediaTranslationInterface = $this->getMediaTranslation();
        $mediaTranslationInterface->setLink($link);
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    public function getSaveWithOriginalName(): bool
    {
        return $this->saveWithOriginalName;
    }

    public function setSaveWithOriginalName(bool $saveWithOriginalName): void
    {
        $this->saveWithOriginalName = $saveWithOriginalName;
    }

    protected function getMediaTranslation(): TranslationInterface
    {
        return $this->getTranslation();
    }

    protected function createTranslation(): MediaTranslationInterface
    {
        return new MediaTranslation();
    }

    public function __toString(): string
    {
        return $this->getName() ?? $this->code ?? '';
    }
}
