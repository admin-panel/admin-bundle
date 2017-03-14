<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional;

use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\Manager\Visitor;
use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use AdminPanel\Symfony\AdminBundle\Menu\MenuBuilder;
use AdminPanel\Symfony\AdminBundle\Menu\MenuHelper;
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
        $element = $this->createElement();
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
            $manager,
            new MenuBuilder\DefaultMenuExtension()
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

        $expectedDocument = new \DOMDocument();
        $expectedDocument->loadHTML($expectedMenu);
        $expectedDocument->preserveWhiteSpace = false;

        $menuDocument = new \DOMDocument();
        $menuDocument->loadHTML($renderedMenu);
        $menuDocument->preserveWhiteSpace = false;

        self::assertEqualXMLStructure(
            $expectedDocument->documentElement,
            $menuDocument->documentElement,
            true
        );
    }

    private function getManager(array $elements) : ManagerInterface
    {
        return new class($elements) implements ManagerInterface
        {
            private $elements = [];

            public function __construct($elements)
            {
                $this->elements = $elements;
            }

            /**
             * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
             * @return \AdminPanel\Symfony\AdminBundle\Admin\Manager
             */
            public function addElement(Element $element)
            {
                $this->elements[$element->getId()] = $element;
            }

            /**
             * @param string $id
             * @return bool
             */
            public function hasElement($id)
            {
                return isset($this->elements[$id]);
            }

            /**
             * @param string $id
             * @return \AdminPanel\Symfony\AdminBundle\Admin\Element
             */
            public function getElement($id)
            {
                return $this->elements[$id] ?? null;
            }

            /**
             * @param int $id
             */
            public function removeElement($id)
            {
                unset($this->elements[$id]);
            }

            /**
             * @return \AdminPanel\Symfony\AdminBundle\Admin\Element[]
             */
            public function getElements()
            {
                return $this->elements;
            }

            /**
             * @param Visitor $visitor
             * @return mixed
             */
            public function accept(Visitor $visitor)
            {
                return true;
            }
        };
    }

    /**
     * @return Element
     */
    private function createElement() : Element
    {
        return new class () extends GenericListElement
        {
            public function getId()
            {
                return 'Users';
            }

            protected function initDataGrid(DataGridFactoryInterface $factory)
            {
            }

            protected function initDataSource(DataSourceFactoryInterface $factory)
            {
            }
        };
    }

    /**
     * @param string $activeName
     * @return MenuHelper
     */
    private function getMenuHelper(string $activeName) : MenuHelper
    {
        return new class($activeName) implements MenuHelper {
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
        };
    }
}
