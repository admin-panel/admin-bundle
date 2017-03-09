<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListBatchDeleteElement;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class MyCRUD extends GenericListBatchDeleteElement
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid('my_datagrid');

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource('doctrine', ['entity' => 'AdminPanelDemoBundle:MyEntity'], 'my_datasource');

        return $datasource;
    }
}
