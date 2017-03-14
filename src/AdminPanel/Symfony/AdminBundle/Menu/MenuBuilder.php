<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Menu;

use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;
use AdminPanel\Symfony\AdminBundle\Menu\Item\ElementItem;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder\MenuExtension;

class MenuBuilder
{
    /**
     * @var array
     */
    private $menuItems;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var MenuExtension
     */
    private $menuExtension;

    /**
     * @param array $menuItems
     * @param ManagerInterface $manager
     * @param MenuExtension $menuExtension
     */
    public function __construct(array $menuItems, ManagerInterface $manager, MenuExtension $menuExtension)
    {
        $this->validateMenuItems($menuItems);
        $this->menuItems = $menuItems;
        $this->manager = $manager;
        $this->menuExtension = $menuExtension;
    }

    /**
     * @return Item
     */
    public function build() : Item
    {
        $items = [];

        foreach ($this->menuItems as $menuItem) {
            $items[] = $this->buildItem($menuItem);
        }

        $root = new Item();

        foreach ($this->menuExtension->extendMenu($items) as $item) {
            $root->addChild($item);
        }

        return $root;
    }

    /**
     * @param array $items
     * @throws \InvalidArgumentException - when one of element in items is not valid
     */
    private function validateMenuItems(array $items)
    {
        foreach ($items as $item) {
            if (!isset($item['name'])) {
                throw new \InvalidArgumentException('"name" key have to be in each menu item element');
            }
        }
    }

    /**
     * @param array $menuItem
     * @return Item
     */
    private function buildItem($menuItem) : Item
    {
        $item = new Item($menuItem['name']);

        if (isset($menuItem['id']) && $menuItem['id'] && $this->manager->hasElement($menuItem['id'])) {
            $item = new ElementItem($menuItem['name'], $this->manager->getElement($menuItem['id']));
        } elseif (isset($menuItem['route']) && $menuItem['route']) {
            $item = new RoutableItem(
                $menuItem['name'],
                $menuItem['route'],
                $menuItem['parameters'] ?? []
            );
        }

        foreach ($menuItem['children'] ?? [] as $childrenItem) {
            $item->addChild($this->buildItem($childrenItem));
        }

        return $item;
    }
}
