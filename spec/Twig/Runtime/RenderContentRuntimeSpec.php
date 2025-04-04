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

namespace spec\Sylius\CmsPlugin\Twig\Runtime;

use PhpSpec\ObjectBehavior;
use Sylius\CmsPlugin\Entity\ContentableInterface;
use Sylius\CmsPlugin\Twig\Parser\ContentParserInterface;
use Sylius\CmsPlugin\Twig\Runtime\RenderContentRuntime;
use Sylius\CmsPlugin\Twig\Runtime\RenderContentRuntimeInterface;

final class RenderContentRuntimeSpec extends ObjectBehavior
{
    public function let(
        ContentParserInterface $contentParser,
    ): void {
        $this->beConstructedWith($contentParser);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RenderContentRuntime::class);
    }

    public function it_implements_render_content_runtime_interface(): void
    {
        $this->shouldHaveType(RenderContentRuntimeInterface::class);
    }

    public function it_renders_content(
        ContentParserInterface $contentParser,
        ContentableInterface $contentableResource,
    ): void {
        $contentParser->parse('content')->willReturn('content');
        $contentableResource->getContent()->willReturn('content');

        $this->renderContent($contentableResource)->shouldReturn('content');
    }
}
