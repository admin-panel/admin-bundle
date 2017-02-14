<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use AdminPanel\Component\DataSource\DataSourceFactory;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin\DataGridAwareElement;
use AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin\DataSourceAwareElement;
use FSi\Component\DataGrid\DataGridFactory;
use PhpSpec\ObjectBehavior;

class ListWorkerSpec extends ObjectBehavior
{
    public function let(
        DataSourceFactory $dataSourceFactory,
        DataGridFactory $dataGridFactory
    ) {
        $this->beConstructedWith($dataSourceFactory, $dataGridFactory);
    }

    public function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware(
        DataGridAwareElement $element,
        DataGridFactory $dataGridFactory
    ) {
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    public function it_mount_datagrid_factory_to_elements_that_are_datasource_aware(
        DataSourceAwareElement $element,
        DataSourceFactory $dataSourceFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();

        $this->mount($element);
    }

    public function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_behave_like_list(
        GenericListElement $element,
        DataSourceFactory $dataSourceFactory,
        DataGridFactory $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    public function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_implements_list_element(
        ListElement $element,
        DataSourceFactory $dataSourceFactory,
        DataGridFactory $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
