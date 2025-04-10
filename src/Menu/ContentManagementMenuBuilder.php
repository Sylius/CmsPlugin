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

namespace Sylius\CmsPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class ContentManagementMenuBuilder
{
    public function __construct(private MenuReorderInterface $menuReorder)
    {
    }

    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        $menu = $menuBuilderEvent->getMenu();

        $cmsRootMenuItem = $menu
            ->addChild('sylius_cms')
            ->setLabel('sylius_cms.ui.cms')
            ->setLabelAttribute('icon', 'tabler:home-edit')
        ;

        $cmsRootMenuItem
            ->addChild('collections', [
                'route' => 'sylius_cms_admin_collection_index',
            ])
            ->setLabel('sylius_cms.ui.collections')
        ;

        $cmsRootMenuItem
            ->addChild('templates', [
                'route' => 'sylius_cms_admin_template_index',
            ])
            ->setLabel('sylius_cms.ui.content_templates')
        ;

        $cmsRootMenuItem
            ->addChild('pages', [
                'route' => 'sylius_cms_admin_page_index',
            ])
            ->setLabel('sylius_cms.ui.pages')
        ;

        $cmsRootMenuItem
            ->addChild('blocks', [
                'route' => 'sylius_cms_admin_block_index',
            ])
            ->setLabel('sylius_cms.ui.blocks')
        ;

        $cmsRootMenuItem
            ->addChild('media', [
                'route' => 'sylius_cms_admin_media_index',
            ])
            ->setLabel('sylius_cms.ui.media')
        ;

        $this->menuReorder->reorder($menu, 'sylius_cms', 'marketing');
    }
}
