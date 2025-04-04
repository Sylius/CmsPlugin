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

namespace Sylius\CmsPlugin\Twig\Runtime;

use Sylius\CmsPlugin\Renderer\ContentElementRendererStrategyInterface;
use Sylius\CmsPlugin\Resolver\BlockResourceResolverInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Twig\Environment;

final class RenderBlockRuntime implements RenderBlockRuntimeInterface
{
    private const DEFAULT_TEMPLATE = '@SyliusCmsPlugin/Shop/Block/show.html.twig';

    public function __construct(
        private BlockResourceResolverInterface $blockResourceResolver,
        private Environment $templatingEngine,
        private ContentElementRendererStrategyInterface $contentElementRendererStrategy,
    ) {
    }

    public function renderBlock(string $code, ?string $template = null, array|ProductInterface|TaxonInterface|null $context = null): string
    {
        $block = $this->blockResourceResolver->findOrLog($code);
        if (null === $block) {
            return '';
        }

        if ($context instanceof TaxonInterface && false === $block->canBeDisplayedForTaxon($context)) {
            return '';
        }

        if ($context instanceof ProductInterface &&
            false === $block->canBeDisplayedForProduct($context) &&
            false === $block->canBeDisplayedForProductInTaxon($context)
        ) {
            return '';
        }

        $blockTemplate = $template ?? $block->getTemplate();

        return $this->templatingEngine->render(
            $blockTemplate ?? self::DEFAULT_TEMPLATE,
            [
                'content' => $this->contentElementRendererStrategy->render($block),
                'context' => $context,
            ],
        );
    }
}
