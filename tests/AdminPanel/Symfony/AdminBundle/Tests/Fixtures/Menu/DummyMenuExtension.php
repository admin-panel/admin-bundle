<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Fixtures\Menu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder\MenuExtension;

final class DummyMenuExtension implements MenuExtension
{
    /**
     * @var array
     */
    private $items;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param Item[] $menu
     * @return Item[]
     */
    public function extendMenu(array $menu) : array
    {
        return array_merge($menu, $this->items);
    }
}