<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\Twig\Extension;

use AdminPanel\Component\DataSource\Field\FieldViewInterface;
use AdminPanel\Symfony\AdminBundle\Twig\Extension\DataSourceExtension;
use AdminPanel\Component\DataSource\DataSourceViewInterface;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Tests\Extension\Fixtures\StubTranslator;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Stanislav Prokopov <stanislav.prokopov@gmail.com>
 */
class DataSourceExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var DataSourceExtension
     */
    protected $extension;

    public function setUp()
    {
        $subPath = version_compare(Kernel::VERSION, '2.7.0', '<') ? 'Symfony/Bridge/Twig/' : '';
        $loader = new \Twig_Loader_Filesystem([
            __DIR__ . '/../../../../../../../vendor/symfony/twig-bridge/' . $subPath . 'Resources/views/Form',
            __DIR__ . '/../../../../../../../src/AdminPanel/Symfony/AdminBundle/Resources/views', // datasource base theme
        ]);

        $rendererEngine = new TwigRendererEngine([
            'form_div_layout.html.twig',
        ]);
        $renderer = new TwigRenderer($rendererEngine);

        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new TranslationExtension(new StubTranslator()));
        $twig->addExtension(new FormExtension($renderer));
        $twig->addGlobal('global_var', 'global_value');
        $this->twig = $twig;

        $this->extension = new DataSourceExtension($this->getContainer(), 'datasource.html.twig');
    }

    public function testDataSourceFilterCount()
    {
        $this->twig->addExtension($this->extension);

        $datasourceView = $this->getDataSourceView('datasource');
        $fieldView1 = $this->createMock(FieldViewInterface::class);
        $fieldView1->expects($this->atLeastOnce())
            ->method('hasAttribute')
            ->with('form')
            ->will($this->returnValue(true));
        $fieldView2 = $this->createMock(FieldViewInterface::class);
        $fieldView2->expects($this->atLeastOnce())
            ->method('hasAttribute')
            ->with('form')
            ->will($this->returnValue(false));
        $fieldView3 = $this->createMock(FieldViewInterface::class);
        $fieldView3->expects($this->atLeastOnce())
            ->method('hasAttribute')
            ->with('form')
            ->will($this->returnValue(true));
        $datasourceView->expects($this->atLeastOnce())
            ->method('getFields')
            ->will($this->returnValue([$fieldView1, $fieldView2, $fieldView3]));

        $this->assertEquals(
            $this->extension->datasourceFilterCount($this->twig, $datasourceView),
            2
        );
    }

    public function testDataSourceRenderBlock()
    {
        $this->twig->addExtension($this->extension);
        $template = $this->createMock('\Twig_Template');

        $template->expects($this->at(0))
            ->method('hasBlock')
            ->with('datasource_datasource_filter')
            ->will($this->returnValue(false));

        $template->expects($this->at(1))
            ->method('getParent')
            ->with([])
            ->will($this->returnValue(false));

        $template->expects($this->at(2))
            ->method('hasBlock')
            ->with('datasource_filter')
            ->will($this->returnValue(true));

        $datasourceView = $this->getDataSourceView('datasource');
        $this->extension->setTheme($datasourceView, $template);

        $template->expects($this->at(3))
            ->method('displayBlock')
            ->with('datasource_filter', [
                'datasource' => $datasourceView,
                'vars' => [],
                'global_var' => 'global_value'
            ])
            ->will($this->returnValue(true));

        $this->extension->datasourceFilter($this->twig, $datasourceView);
    }

    public function testDataSourceRenderBlockFromParent()
    {
        $this->twig->addExtension($this->extension);

        if (method_exists($this->twig, 'initRuntime')) {
            $this->twig->initRuntime();
        }

        $parent = $this->createMock('\Twig_Template');
        $template = $this->createMock('\Twig_Template');

        $template->expects($this->at(0))
            ->method('hasBlock')
            ->with('datasource_datasource_filter')
            ->will($this->returnValue(false));

        $template->expects($this->at(1))
            ->method('getParent')
            ->with([])
            ->will($this->returnValue(false));

        $template->expects($this->at(2))
            ->method('hasBlock')
            ->with('datasource_filter')
            ->will($this->returnValue(false));

        $template->expects($this->at(3))
            ->method('getParent')
            ->with([])
            ->will($this->returnValue($parent));

        $parent->expects($this->at(0))
            ->method('hasBlock')
            ->with('datasource_filter')
            ->will($this->returnValue(true));

        $datasourceView = $this->getDataSourceView('datasource');
        $this->extension->setTheme($datasourceView, $template);

        $parent->expects($this->at(1))
            ->method('displayBlock')
            ->with('datasource_filter', [
                'datasource' => $datasourceView,
                'vars' => [],
                'global_var' => 'global_value'
            ])
            ->will($this->returnValue(true));

        $this->extension->datasourceFilter($this->twig, $datasourceView);
    }

    private function getRouter()
    {
        $router = $this->createMock('\Symfony\Component\Routing\RouterInterface');
        $router->expects($this->any())
            ->method('generate')
            ->will($this->returnValue('some_route'));

        return $router;
    }

    private function getContainer()
    {
        $container = $this->createMock(
            '\Symfony\Component\DependencyInjection\ContainerInterface'
        );
        $container->expects($this->any())
            ->method('get')
            ->with('router')
            ->will($this->returnValue($this->getRouter()));

        return $container;
    }

    private function getDataSourceView($name)
    {
        $datasourceView = $this->getMockBuilder('AdminPanel\Component\DataSource\DataSourceViewInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $datasourceView->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));

        return $datasourceView;
    }
}
