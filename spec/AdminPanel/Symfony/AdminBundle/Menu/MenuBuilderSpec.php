<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Menu;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use AdminPanel\Symfony\AdminBundle\Menu\Item\ElementItem;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MenuBuilderSpec extends ObjectBehavior
{
    function let(Manager $manager)
    {
        $this->beConstructedWith(
            [
                ["id" => "admin_users", "name" => "Users"],
                ["id" => "admin_custom_template_users", "name" => "Users (custom template)"],
                ["id" => "admin_users_dbal", "name" => "Users (dbal)"]
            ],
            $manager,
            new MenuBuilder\DefaultMenuExtension()
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MenuBuilder::class);
    }

    function it_builds_menu_base_on_given_parameters(
        Manager $manager,
        Element $adminUsersElement,
        Element $adminCustomTemplateUsers
    ) {
        $manager->hasElement('admin_users')->willReturn(true);
        $manager->getElement('admin_users')->willReturn($adminUsersElement);

        $manager->hasElement('admin_custom_template_users')->willReturn(true);
        $manager->getElement('admin_custom_template_users')->willReturn($adminCustomTemplateUsers);

        $manager->hasElement('admin_users_dbal')->willReturn(false);

        $menuItems = $this->build()->getChildren();

        $menuItems->shouldHaveCount(3);
        $menuItems['Users']->shouldHaveType(ElementItem::class);
        $menuItems['Users (custom template)']->shouldHaveType(ElementItem::class);
        $menuItems['Users (dbal)']->shouldHaveType(Item::class);
    }

    function it_builds_menu_with_routable_items_base_on_given_parameters(
        Manager $manager
    ) {
       $this->beConstructedWith(
           [
               ["route" => "test_route", "name" => "Users (dbal)"]
           ],
           $manager,
           new MenuBuilder\DefaultMenuExtension()
       );
        $manager->hasElement('admin_users_dbal')->willReturn(false);

        $menuItems = $this->build()->getChildren();

        $menuItems->shouldHaveCount(1);
        $menuItems['Users (dbal)']->shouldHaveType(RoutableItem::class);
        $menuItems['Users (dbal)']->getRoute()->shouldBe('test_route');
    }

    function it_allow_to_decide_about_menu_order_and_items_by_modify_menu_in_extension(
        Manager $manager,
        MenuBuilder\MenuExtension $menuExtension
    ) {
        $this->beConstructedWith(
            [
                ["route" => "test_route", "name" => "Users (dbal)"]
            ],
            $manager,
            $menuExtension
        );

        $menuExtension
            ->extendMenu(Argument::any())
            ->willReturn([new RoutableItem("Users (dbal)", "test_route"), new Item('test123')]);

        $menuItems = $this->build()->getChildren();

        $menuItems->shouldHaveCount(2);
        $menuItems['Users (dbal)']->shouldHaveType(RoutableItem::class);
        $menuItems['Users (dbal)']->getRoute()->shouldBe('test_route');
        $menuItems['test123']->shouldHaveType(Item::class);
        $menuItems['test123']->getName()->shouldBe('test123');
    }

    function it_allow_to_creates_sub_menus(
        Manager $manager
    ) {
        $this->beConstructedWith(
            [
                ["route" => "test_route", "name" => "Users (dbal)"],
                [
                    "name" => 'Other menu',
                    "children" => [
                        ["route" => "test_route", "name" => "Test"],
                        ["route" => "test_route", "name" => "Users (dbal)"]
                    ]
                ]
            ],
            $manager,
            new MenuBuilder\DefaultMenuExtension()
        );

        $menuItems = $this->build()->getChildren();

        $menuItems->shouldHaveCount(2);
        $menuItems['Users (dbal)']->shouldHaveType(RoutableItem::class);
        $menuItems['Users (dbal)']->getRoute()->shouldBe('test_route');
        $menuItems['Other menu']->shouldHaveType(Item::class);

        $otherMenuChildren = $menuItems['Other menu']->getChildren();
        $otherMenuChildren->shouldHaveCount(2);
        $otherMenuChildren['Test']->shouldHaveType(RoutableItem::class);
        $otherMenuChildren['Users (dbal)']->shouldHaveType(RoutableItem::class);
    }

    function its_menu_items_in_the_constructor_need_to_have_expected_format(
        Manager $manager
    ) {
        $this->beConstructedWith(
            [
                ["route" => "test_route"]
            ],
            $manager,
            new MenuBuilder\DefaultMenuExtension()
        );

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
