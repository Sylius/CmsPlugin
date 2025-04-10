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

namespace Tests\Sylius\CmsPlugin\Unit\Menu;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Sylius\CmsPlugin\Menu\ContentManagementMenuBuilder;
use Sylius\CmsPlugin\Menu\MenuReorderInterface;

final class ContentManagementMenuBuilderTest extends TestCase
{
    /** @var MockObject&MenuReorderInterface */
    private MockObject $menuReorder;

    private ContentManagementMenuBuilder $contentManagementMenuBuilder;

    protected function setUp(): void
    {
        $this->menuReorder = $this->createMock(MenuReorderInterface::class);

        $this->contentManagementMenuBuilder = new ContentManagementMenuBuilder($this->menuReorder);
    }

    public function testBuildsMenu(): void
    {
        $menuBuilderEvent = $this->createMock(MenuBuilderEvent::class);
        $menu = $this->createMock(ItemInterface::class);
        $cmsRootMenuItem = $this->createMock(ItemInterface::class);

        $collectionsItem = $this->createMock(ItemInterface::class);
        $templatesItem = $this->createMock(ItemInterface::class);
        $pagesItem = $this->createMock(ItemInterface::class);
        $blocksItem = $this->createMock(ItemInterface::class);
        $mediaItem = $this->createMock(ItemInterface::class);

        $menuBuilderEvent->expects(self::once())->method('getMenu')->willReturn($menu);

        $menu->expects(self::once())->method('addChild')->with('sylius_cms')->willReturn($cmsRootMenuItem);

        $cmsRootMenuItem
            ->expects(self::once())
            ->method('setLabel')
            ->with('sylius_cms.ui.cms')
            ->willReturn($cmsRootMenuItem)
        ;

        $cmsRootMenuItem
            ->expects(self::once())
            ->method('setLabelAttribute')
            ->with('icon', 'tabler:home-edit')
            ->willReturn($cmsRootMenuItem)
        ;

        $cmsRootMenuItem->method('addChild')->willReturnOnConsecutiveCalls(
            $collectionsItem,
            $templatesItem,
            $pagesItem,
            $blocksItem,
            $mediaItem,
        );
        $collectionsItem
            ->expects(self::once())
            ->method('setLabel')
            ->with('sylius_cms.ui.collections')
            ->willReturn($collectionsItem)
        ;

        $templatesItem
            ->expects(self::once())
            ->method('setLabel')
            ->with('sylius_cms.ui.content_templates')
            ->willReturn($templatesItem)
        ;

        $pagesItem
            ->expects(self::once())
            ->method('setLabel')
            ->with('sylius_cms.ui.pages')
            ->willReturn($pagesItem)
        ;

        $blocksItem
            ->expects(self::once())
            ->method('setLabel')
            ->with('sylius_cms.ui.blocks')
            ->willReturn($blocksItem)
        ;

        $mediaItem
            ->expects(self::once())
            ->method('setLabel')
            ->with('sylius_cms.ui.media')
            ->willReturn($mediaItem)
        ;

        $this->menuReorder
            ->expects(self::once())
            ->method('reorder')
            ->with($cmsRootMenuItem, 'sylius_cms', 'marketing')
        ;

        $this->contentManagementMenuBuilder->buildMenu($menuBuilderEvent);
    }
}
