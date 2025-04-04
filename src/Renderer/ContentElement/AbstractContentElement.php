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

use Twig\Environment;

abstract class AbstractContentElement implements ContentElementRendererInterface
{
    protected string $template;

    protected Environment $twig;

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function setTwigEnvironment(Environment $twig): void
    {
        $this->twig = $twig;
    }
}
