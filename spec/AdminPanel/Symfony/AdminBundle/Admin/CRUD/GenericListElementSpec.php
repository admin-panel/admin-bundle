<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Component\DataSource\DataSourceFactory;
use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use AdminPanel\Component\DataGrid\DataGridFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericListElementSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyList');
        $this->beConstructedWith([]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement');
    }

    public function it_is_list_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement');
    }

    public function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    public function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    public function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid(DataGridFactory $factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataGrid should return instanceof AdminPanel\\Component\\DataGrid\\DataGridInterface"))
            ->during('createDataGrid');
    }

    public function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource(DataSourceFactory $factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataSource should return instanceof AdminPanel\\Component\\DataSource\\DataSourceInterface"))
            ->during('createDataSource');
    }

    public function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn([
            'allow_delete' => true,
            'template_list' => null,
        ]);
    }
}
