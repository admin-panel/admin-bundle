<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Fixtures;

use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement;

final class ListElement extends GenericListElement
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
}