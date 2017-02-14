<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use AdminPanel\Symfony\AdminBundle\Event\MenuEvent;
use AdminPanel\Symfony\AdminBundle\Menu\Builder\Exception\InvalidYamlStructureException;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class MainMenuListenerSpec extends ObjectBehavior
{
    public function let(Manager $manager)
    {
        $prophet = new Prophet();
        $manager->getElement(Argument::type('string'))->will(function ($args) use ($prophet) {
            if ($args[0] == 'non_existing') {
                throw new \Exception(sprintf('Element %s does not exist', $args[0]));
            };
            $element = $prophet->prophesize('AdminPanel\Symfony\AdminBundle\Admin\Element');
            $element->getId()->willReturn($args[0]);
            return $element;
        });

        $manager->hasElement(Argument::type('string'))->will(function ($args) {
            return $args[0] != 'non_existing';
        });
        $this->beConstructedWith($manager, __DIR__ . '/admin_menu.yml');
    }

    public function it_throws_exception_when_yaml_definition_of_menu_is_invalid(Manager $manager, MenuEvent $event)
    {
        $menuYaml = __DIR__ . '/invalid_admin_menu.yml';
        $this->beConstructedWith($manager, $menuYaml);

        $this->shouldThrow(new InvalidYamlStructureException(
            sprintf('File "%s" should contain top level "menu:" key', $menuYaml)
        ))->during('createMainMenu', [$event]);
    }

    public function it_build_menu()
    {
        $menu = $this->createMainMenu(new MenuEvent(new Item()));

        $menu->shouldHaveItem('News', 'news');
        $menu->shouldHaveItem('article', 'article');
        $menu->shouldHaveItem('admin.menu.structure', false);
        $menu->shouldHaveItemThatHaveChild('admin.menu.structure', 'home_page', 'home_page');
        $menu->shouldHaveItemThatHaveChild('admin.menu.structure', 'Contact', 'contact');
        $menu->shouldHaveItemThatHaveChild('admin.menu.structure', 'Offer', 'offer');

        $offerItem = $menu->getChildren()['admin.menu.structure']->getChildren()['Offer'];
        $offerItem->getOption('elements')[0]->getId()->shouldReturn('category');
        $offerItem->getOption('elements')[1]->getId()->shouldReturn('product');
    }

    public function getMatchers()
    {
        return [
            'haveItem' => function (Item $menu, $itemName, $elementId = false) {
                $items = $menu->getChildren();
                foreach ($items as $item) {
                    if ($item->getName() === $itemName) {
                        if (!$elementId) {
                            return true;
                        }

                        /** @var ElementItem $item */
                        return $item->getElement()->getId() === $elementId;
                    }
                }
                return false;
            },
            'haveItemThatHaveChild' => function (Item $menu, $itemName, $childName, $elementId = false) {
                foreach ($menu->getChildren() as $item) {
                    if ($item->getName() === $itemName && $item->hasChildren()) {
                        foreach ($item->getChildren() as $child) {
                            if ($child->getName() === $childName) {
                                if (!$elementId) {
                                    return true;
                                }

                                /** @var ElementItem $child */
                                return $child->getElement()->getId() === $elementId;
                            }
                        }
                    }
                }
                return false;
            }
        ];
    }
}
