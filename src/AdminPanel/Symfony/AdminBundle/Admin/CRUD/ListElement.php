<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\Element;

interface ListElement extends DataSourceAwareInterface, DataGridAwareInterface, Element
{
    /**
     * @return \AdminPanel\Component\DataGrid\DataGrid|null
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function createDataGrid();

    /**
     * @return \AdminPanel\Component\DataSource\DataSource|null
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function createDataSource();
}
