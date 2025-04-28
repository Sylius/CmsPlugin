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

namespace Sylius\CmsPlugin\Renderer;

use Sylius\CmsPlugin\Entity\ContentElementsAwareInterface;
use Sylius\CmsPlugin\Renderer\ContentElement\ContentElementRendererInterface;
use Sylius\CmsPlugin\Twig\Parser\ContentParserInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContentElementRendererStrategy implements ContentElementRendererStrategyInterface
{
    /**
     * @param ContentElementRendererInterface[] $renderers
     */
    public function __construct(
        private ContentParserInterface $contentParser,
        private LocaleContextInterface $localeContext,
        private iterable $renderers,
    ) {
    }

    public function render(ContentElementsAwareInterface $item): string
    {
        $content = '';

        $locale = $this->localeContext->getLocaleCode();
        foreach ($item->getContentElements() as $contentElement) {
            if ($contentElement->getLocale() !== $locale) {
                continue;
            }

            foreach ($this->renderers as $renderer) {
                if ($renderer->supports($contentElement)) {
                    $content .= html_entity_decode($renderer->render($contentElement), \ENT_QUOTES);

                    break;
                }
            }
        }

        return $this->contentParser->parse($content);
    }
}
