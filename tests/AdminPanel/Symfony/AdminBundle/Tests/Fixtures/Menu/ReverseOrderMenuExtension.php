<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Fixtures\Menu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder\MenuExtension;

final class ReverseOrderMenuExtension implements MenuExtension
{
    /**
     * @param Item[] $menu
     * @return Item[]
     */
    public function extendMenu(array $menu) : array
    {
        return array_reverse($menu);
    }
}