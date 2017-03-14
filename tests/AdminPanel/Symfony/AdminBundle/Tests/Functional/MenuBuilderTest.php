<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder;
use AdminPanel\Symfony\AdminBundle\Menu\MenuHelper;
use AdminPanel\Symfony\AdminBundle\Tests\Fixtures\DummyElementManager;
use AdminPanel\Symfony\AdminBundle\Tests\Fixtures\ListElement;
use AdminPanel\Symfony\AdminBundle\Tests\Fixtures\Menu\DummyMenuExtension;
use AdminPanel\Symfony\AdminBundle\Tests\Fixtures\Menu\DummyMenuHelper;
use AdminPanel\Symfony\AdminBundle\Tests\Fixtures\Menu\ReverseOrderMenuExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MenuBuilderTest extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->container = static::$kernel
            ->getContainer()
        ;
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            'test',
            true
        );
    }

    public function test_that_built_menu_is_rendered_correctly()
    {
        $element = new ListElement();
        $manager = $this->getManager(['Users' => $element]);

        $menuBuilder = new MenuBuilder(
            [
                ['id' => 'Users', 'name' => 'Users'],
                ['route' => 'my_route', 'name' => 'Custom'],
                ['route' => 'my_route', 'name' => 'Custom 2', 'parameters' => ['test' => 'yes']],
                [
                    'name' => 'Root menu',
                    'children' => [
                        ['id' => 'Users', 'name' => 'Users 123'],
                        ['route' => 'my_route', 'name' => 'Custom 234']
                    ]
                ]
            ],
            $manager
        );

        $menu = $menuBuilder->build();

        $expectedMenu = <<<EOL
<ul>
    <li class="first">
        <a href="/list/Users">Users</a>
    </li>
    <li class="active">
        <a href="/my-route">Custom</a>
    </li>
    <li>
        <a href="/my-route?test=yes">Custom 2</a>
    </li>
    <li class="last">
        Root menu
        <ul>
            <li>
                <a href="/list/Users">Users 123</a>
            </li>
            <li>
                <a href="/my-route">Custom 234</a>
            </li>
        </ul>
    </li>
</ul>
EOL;

        $renderedMenu = $this
            ->container
            ->get('twig')
            ->render(
                'Admin/menu.html.twig',
                ['root' => $menu, 'menuHelper' => $this->getMenuHelper($activeName = 'Custom')]
            )
        ;

        self::assertHTMLEquals($expectedMenu, $renderedMenu);
    }

    public function test_that_we_can_add_menu_item_in_the_extension()
    {
        $element = new ListElement();
        $manager = $this->getManager(['Users' => $element]);
        $menuExtension = new DummyMenuExtension([new RoutableItem('Element added by extension', 'my_route')]);

        $menuBuilder = new MenuBuilder(
            [
                ['id' => 'Users', 'name' => 'Users'],
                ['route' => 'my_route', 'name' => 'Custom'],
                ['route' => 'my_route', 'name' => 'Custom 2', 'parameters' => ['test' => 'yes']],
                [
                    'name' => 'Root menu',
                    'children' => [
                        ['id' => 'Users', 'name' => 'Users 123'],
                        ['route' => 'my_route', 'name' => 'Custom 234']
                    ]
                ]
            ],
            $manager
        );
        $menuBuilder->setMenuExtension($menuExtension);

        $menu = $menuBuilder->build();

        $expectedMenu = <<<EOL
<ul>
    <li class="first">
        <a href="/list/Users">Users</a>
    </li>
    <li class="active">
        <a href="/my-route">Custom</a>
    </li>
    <li>
        <a href="/my-route?test=yes">Custom 2</a>
    </li>
    <li>
        Root menu
        <ul>
            <li>
                <a href="/list/Users">Users 123</a>
            </li>
            <li>
                <a href="/my-route">Custom 234</a>
            </li>
        </ul>
    </li>
    <li class="last">
        <a href="/my-route">Element added by extension</a>
    </li>
</ul>
EOL;

        $renderedMenu = $this
            ->container
            ->get('twig')
            ->render(
                'Admin/menu.html.twig',
                ['root' => $menu, 'menuHelper' => $this->getMenuHelper($activeName = 'Custom')]
            )
        ;

        self::assertHTMLEquals($expectedMenu, $renderedMenu);
    }

    public function test_that_extension_can_change_order_of_menu()
    {
        $element = new ListElement();
        $manager = $this->getManager(['Users' => $element]);
        $extension = new ReverseOrderMenuExtension();

        $menuBuilder = new MenuBuilder(
            [
                ['id' => 'Users', 'name' => 'Users'],
                ['route' => 'my_route', 'name' => 'Custom'],
                ['route' => 'my_route', 'name' => 'Custom 2', 'parameters' => ['test' => 'yes']],
                [
                    'name' => 'Root menu',
                    'children' => [
                        ['id' => 'Users', 'name' => 'Users 123'],
                        ['route' => 'my_route', 'name' => 'Custom 234']
                    ]
                ]
            ],
            $manager
        );
        $menuBuilder->setMenuExtension($extension);

        $menu = $menuBuilder->build();

        $expectedMenu = <<<EOL
<ul>
    <li class="first">
        Root menu
        <ul>
            <li>
                <a href="/list/Users">Users 123</a>
            </li>
            <li>
                <a href="/my-route">Custom 234</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="/my-route?test=yes">Custom 2</a>
    </li>
    <li class="active">
        <a href="/my-route">Custom</a>
    </li>
    <li class="last">
        <a href="/list/Users">Users</a>
    </li>
</ul>
EOL;

        $renderedMenu = $this
            ->container
            ->get('twig')
            ->render(
                'Admin/menu.html.twig',
                ['root' => $menu, 'menuHelper' => $this->getMenuHelper($activeName = 'Custom')]
            )
        ;

        self::assertHTMLEquals($expectedMenu, $renderedMenu);
    }

    /**
     * @param string $expectedHTML
     * @param string $renderedHTML
     */
    protected static function assertHTMLEquals(string $expectedHTML, string $renderedHTML)
    {
        $expectedDocument = new \DOMDocument();
        $expectedDocument->loadHTML($expectedHTML);
        $expectedDocument->preserveWhiteSpace = false;

        $menuDocument = new \DOMDocument();
        $menuDocument->loadHTML($renderedHTML);
        $menuDocument->preserveWhiteSpace = false;

        self::assertEqualXMLStructure(
            $expectedDocument->documentElement,
            $menuDocument->documentElement,
            true
        );
    }

    private function getManager(array $elements) : ManagerInterface
    {
        return new DummyElementManager($elements);
    }

    /**
     * @param string $activeName
     * @return MenuHelper
     */
    private function getMenuHelper(string $activeName) : MenuHelper
    {
        return new DummyMenuHelper($activeName);
    }
}
