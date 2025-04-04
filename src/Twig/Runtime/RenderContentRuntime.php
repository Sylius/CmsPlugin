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

namespace Sylius\CmsPlugin\Twig\Runtime;

use Sylius\CmsPlugin\Entity\ContentableInterface;
use Sylius\CmsPlugin\Twig\Parser\ContentParserInterface;

final class RenderContentRuntime implements RenderContentRuntimeInterface
{
    public function __construct(private ContentParserInterface $contentParser)
    {
    }

    public function renderContent(ContentableInterface $contentableResource): string
    {
        $content = html_entity_decode((string) $contentableResource->getContent(), \ENT_QUOTES);

        return $this->contentParser->parse($content);
    }
}
