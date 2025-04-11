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

namespace Sylius\CmsPlugin\Provider;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ResourceTemplateProvider implements ResourceTemplateProviderInterface
{
    /** @var array<string, array<string>> */
    private array $templates = [];

    public function __construct(ParameterBagInterface $params)
    {
        if ($params->has('sylius_cms.templates.pages')) {
            $pageTemplates = $params->get('sylius_cms.templates.pages');
            if (is_array($pageTemplates)) {
                $this->templates['pages'] = $pageTemplates;
            }
        }

        if ($params->has('sylius_cms.templates.blocks')) {
            $blockTemplates = $params->get('sylius_cms.templates.blocks');
            if (is_array($blockTemplates)) {
                $this->templates['blocks'] = $blockTemplates;
            }
        }
    }

    public function getPageTemplates(): array
    {
        $keys = ['sylius.ui.default'];
        $values = ['@SyliusCmsPlugin/shop/page/show.html.twig'];

        return array_combine(
            array_merge($keys, $this->templates['pages']),
            array_merge($values, $this->templates['pages']),
        );
    }

    public function getBlockTemplates(): array
    {
        $keys = ['sylius.ui.default'];
        $values = ['@SyliusCmsPlugin/shop/block/show.html.twig'];

        return array_combine(
            array_merge($keys, $this->templates['blocks']),
            array_merge($values, $this->templates['blocks']),
        );
    }
}
