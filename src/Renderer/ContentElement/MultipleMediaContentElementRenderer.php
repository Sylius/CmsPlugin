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

namespace Sylius\CmsPlugin\Renderer\ContentElement;

use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\MultipleMediaContentElementType;
use Sylius\CmsPlugin\Repository\MediaRepositoryInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderMediaRuntimeInterface;

final class MultipleMediaContentElementRenderer extends AbstractContentElement
{
    public function __construct(
        private RenderMediaRuntimeInterface $renderMediaRuntime,
        private MediaRepositoryInterface $mediaRepository,
    ) {
    }

    public function supports(ContentConfigurationInterface $contentConfiguration): bool
    {
        return MultipleMediaContentElementType::TYPE === $contentConfiguration->getType();
    }

    public function render(ContentConfigurationInterface $contentConfiguration): string
    {
        $media = [];
        $codes = $contentConfiguration->getConfiguration()['multiple_media'];

        /** @var MediaInterface[] $mediaEntities */
        $mediaEntities = $this->mediaRepository->findBy(['code' => $codes]);
        $mediaEntitiesByCode = array_reduce($mediaEntities, static function (array $result, MediaInterface $media) {
            $result[$media->getCode()] = $media;

            return $result;
        }, []);

        foreach ($codes as $code) {
            $media[] = [
                'renderedContent' => $this->renderMediaRuntime->renderMedia($code),
                'entity' => $mediaEntitiesByCode[$code],
            ];
        }

        return $this->twig->render('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $this->template,
            'media' => $media,
        ]);
    }
}
