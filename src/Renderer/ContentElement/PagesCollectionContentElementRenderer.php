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

namespace Sylius\CmsPlugin\Renderer\ContentElement;

use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Form\Type\ContentElements\PagesCollectionContentElementType;
use Sylius\CmsPlugin\Repository\CollectionRepositoryInterface;

final class PagesCollectionContentElementRenderer extends AbstractContentElement
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
    ) {
    }

    public function supports(ContentConfigurationInterface $contentConfiguration): bool
    {
        return PagesCollectionContentElementType::TYPE === $contentConfiguration->getType();
    }

    public function render(ContentConfigurationInterface $contentConfiguration): string
    {
        $code = $contentConfiguration->getConfiguration()['pages_collection'];
        if (null === $code) {
            return '';
        }

        /** @var CollectionInterface|null $collection */
        $collection = $this->collectionRepository->findOneBy(['code' => $code]);

        return $this->twig->render('@SyliusCmsPlugin/Shop/ContentElement/index.html.twig', [
            'content_element' => $this->template,
            'collection' => $collection?->getPages(),
        ]);
    }
}
