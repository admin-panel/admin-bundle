<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;

class MyList extends GenericListElement
{
    public function getId()
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
    }

    public function getDataIndexer()
    {
    }

    public function saveDataGrid()
    {
    }

    public function delete($object)
    {
    }
}
