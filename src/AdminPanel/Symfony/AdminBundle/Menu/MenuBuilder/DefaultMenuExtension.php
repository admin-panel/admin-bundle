<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;

final class DefaultMenuExtension implements MenuExtension
{
    /**
     * @param Item[] $menu
     * @return Item[]
     */
    public function extendMenu(array $menu) : array
    {
        return $menu;
    }
}