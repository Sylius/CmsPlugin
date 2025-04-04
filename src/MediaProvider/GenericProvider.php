<?php

/*
 * This file is part of the Sylius Cms Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\MediaProvider;

use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Uploader\MediaUploaderInterface;
use Twig\Environment;

final class GenericProvider implements ProviderInterface
{
    public function __construct(
        private MediaUploaderInterface $uploader,
        private Environment $twigEngine,
        private string $template,
        private string $pathPrefix,
    ) {
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function render(
        MediaInterface $media,
        ?string $template = null,
        array $options = [],
    ): string {
        return $this->twigEngine->render($template ?? $this->template, array_merge(['media' => $media], $options));
    }

    public function upload(MediaInterface $media): void
    {
        $this->uploader->upload($media, $this->pathPrefix);
    }
}
