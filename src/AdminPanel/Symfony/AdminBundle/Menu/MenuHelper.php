<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Menu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;

interface MenuHelper
{
    /**
     * @param string $currentPath
     * @param Item $item
     * @return bool
     */
    public function isActive(string $currentPath, Item $item) : bool;
}