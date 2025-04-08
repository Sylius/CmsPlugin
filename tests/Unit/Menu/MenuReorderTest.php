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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Menu\MenuReorder;
use Sylius\CmsPlugin\Menu\MenuReorderInterface;

final class MenuReorderTest extends TestCase
{
    private MenuReorderInterface $menuReorder;

    protected function setUp(): void
    {
        $this->menuReorder = new MenuReorder();
    }

    public function testImplementsMenuReorderInterface(): void
    {
        self::assertInstanceOf(MenuReorderInterface::class, $this->menuReorder);
    }

    public function testReordersMenuItems(): void
    {
        $menu = $this->createMock(ItemInterface::class);
        $item1 = $this->createMock(ItemInterface::class);
        $item2 = $this->createMock(ItemInterface::class);
        $item3 = $this->createMock(ItemInterface::class);

        $menu->expects(self::once())->method('getChildren')->willReturn([
            'item1' => $item1,
            'item2' => $item2,
            'item3' => $item3,
        ]);

        $menu->expects(self::once())->method('getChild')->with('item2')->willReturn($item2);

        $menu->expects(self::once())->method('setChildren')->with([
            'item1' => $item1,
            'item3' => $item3,
            'item2' => $item2,
        ]);

        $this->menuReorder->reorder($menu, 'item2', 'item3');
    }

    /** @param array<array-key, string> $itemsKeys */
    #[DataProvider('getNoReorderData')]
    public function testDoesNotReorder(array $itemsKeys, string $newItemKey, string $targetItemKey): void
    {
        $menu = $this->createMock(ItemInterface::class);

        $items = [];
        foreach ($itemsKeys as $item) {
            $items[$item] = $this->createMock(ItemInterface::class);
        }

        $menu->expects(self::once())->method('getChildren')->willReturn($items);
        $menu->expects(self::once())->method('getChild')->with($newItemKey)->willReturn($items[$newItemKey] ?? null);
        $menu->expects(self::never())->method('setChildren');

        $this->menuReorder->reorder($menu, $newItemKey, $targetItemKey);
    }

    /**
     * @return iterable<array{
     *     itemsKeys: array<array-key, string>,
     *     newItemKey: string,
     *     targetItemKey: string,
     * }>
     */
    public static function getNoReorderData(): iterable
    {
        yield 'new item not found' => [
            'itemsKeys' => ['item1', 'item3'],
            'newItemKey' => 'item2',
            'targetItemKey' => 'item3',
        ];

        yield 'target item not found' => [
            'itemsKeys' => ['item1', 'item2'],
            'newItemKey' => 'item1',
            'targetItemKey' => 'item3',
        ];

        yield 'no reorder needed' => [
            'itemsKeys' => ['item1', 'item2'],
            'newItemKey' => 'item1',
            'targetItemKey' => 'item1',
        ];
    }
}
