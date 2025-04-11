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
use Sylius\CmsPlugin\Form\Type\ContentElements\SpacerContentElementType;

final class SpacerContentElementRenderer extends AbstractContentElement
{
    public function supports(ContentConfigurationInterface $contentConfiguration): bool
    {
        return SpacerContentElementType::TYPE === $contentConfiguration->getType();
    }

    public function render(ContentConfigurationInterface $contentConfiguration): string
    {
        $configuration = (int) $contentConfiguration->getConfiguration()['spacer'];

        return $this->twig->render('@SyliusCmsPlugin/shop/content_element/index.html.twig', [
            'content_element' => $this->template,
            'spacer_height' => $configuration,
        ]);
    }
}
