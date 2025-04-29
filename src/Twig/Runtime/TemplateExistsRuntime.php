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

use Sylius\CmsPlugin\Entity\StaticTemplateAwareInterface;
use Twig\Environment;
use Twig\Error\LoaderError;

final readonly class TemplateExistsRuntime implements TemplateExistsRuntimeInterface
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function exists(StaticTemplateAwareInterface|string|null $templateAware): bool
    {
        $template = $templateAware instanceof StaticTemplateAwareInterface ?
            $templateAware->getTemplate() :
            $templateAware
        ;
        if (null === $template || '' === $template) {
            return false;
        }

        try {
            $this->twig->load($template);
        } catch (LoaderError) {
            return false;
        }

        return true;
    }
}
