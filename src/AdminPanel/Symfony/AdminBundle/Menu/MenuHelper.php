<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Menu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class MenuHelper
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param string $currentPath
     * @param Item $item
     * @return bool
     */
    public function isActive(string $currentPath, Item $item) : bool
    {
        $paths = $this->collectPathsFromAllChildren($item);

        return in_array($currentPath, $paths);
    }

    /**
     * @param Item $item
     * @return array
     */
    private function collectPathsFromAllChildren(Item $item) : array
    {
        $paths = $this->generatePath($item, []);

        foreach ($item->getChildren() as $item) {
            $paths = array_merge($paths, $this->collectPathsFromAllChildren($item));
        }

        return $paths;
    }

    /**
     * @param Item $item
     * @param array $paths
     * @return array
     */
    private function generatePath(Item $item, array $paths) : array
    {
        if ($item instanceof RoutableItem) {
            $paths[] = $this->generator->generate($item->getRoute(), $item->getRouteParameters());
        }

        return $paths;
    }
}