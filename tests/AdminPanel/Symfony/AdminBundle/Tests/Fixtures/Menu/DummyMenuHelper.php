<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Fixtures\Menu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\MenuHelper;

final class DummyMenuHelper implements MenuHelper
{
    /**
     * @var string
     */
    private $activeName;

    /**
     * @param $activeName
     */
    public function __construct($activeName)
    {
        $this->activeName = $activeName;
    }

    /**
     * @param string $currentPath
     * @param Item $item
     * @return bool
     */
    public function isActive(string $currentPath, Item $item) : bool
    {
        return $item->getName() === $this->activeName;
    }
}