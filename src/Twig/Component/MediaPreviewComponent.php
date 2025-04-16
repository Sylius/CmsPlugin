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

namespace Sylius\CmsPlugin\Twig\Component;

use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\TwigHooks\Twig\Component\HookableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;

final class MediaPreviewComponent
{
    use HookableComponentTrait;

    public MediaInterface $resource;

    public string $imageTemplate;

    public string $videoTemplate;

    public string $fileTemplate;

    public string $template;

    #[PostMount]
    public function postMount(): void
    {
        $this->template = match ($this->resource->getType()) {
            MediaInterface::IMAGE_TYPE => $this->imageTemplate,
            MediaInterface::VIDEO_TYPE => $this->videoTemplate,
            default => $this->fileTemplate,
        };
    }

    #[ExposeInTemplate('path')]
    public function getPath(): ?string
    {
        return $this->resource->getPath();
    }

    #[ExposeInTemplate('code')]
    public function getCode(): ?string
    {
        return $this->resource->getCode();
    }
}
