<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Element;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ListElement;
use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

final class DbalUserElement extends ListElement
{
    /**
     * Initialize DataGrid.
     *
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid(
            $this->getId() // this is the ID of the element's datagrid
        );
        $datagrid->addColumn('username', 'text', [
            'label' => 'Username',
            'field_mapping' => ['[username]'],
        ]);
        $datagrid->addColumn('hasNewsletter', 'boolean', [
            'label' => 'Has newsletter?',
            'field_mapping' => ['[hasNewsletter]'],
        ]);
        $datagrid->addColumn('createdAt', 'datetime', [
            'label' => 'Created at',
            'field_mapping' => ['[createdAt]'],
            'input_field_format' => 'Y-m-d H:i:s'
        ]);
        $datagrid->addColumn('credits', 'money', [
            'label' => 'Credits',
            'currency' => 'EUR',
            'field_mapping' => ['[credits]'],
        ]);
        $datagrid->addColumn('actions', 'action', [
            'label' => 'Actions',
            'field_mapping' => ['[id]'],
            'actions' => [
                'custom' => [
                    'url_attr' => [
                        'class' => 'btn btn-warning btn-small-horizontal',
                        'title' => 'Custom'
                    ],
                    'route_name' => 'custom_action',
                    'parameters_field_mapping' => [
                        'id' => '[id]'
                    ],
                ]
            ],
        ]);

        return $datagrid;
    }

    /**
     * Initialize DataSource.
     *
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->getObjectManager()->getConnection()->createQueryBuilder();
        $queryBuilder
            ->select('u.id, u.username, u.hasNewsletter, u.credits, u.createdAt')
            ->from('admin_panel_users', 'u')
            ->orderBy('u.createdAt', 'DESC');

        $datasource = $factory->createDataSource('doctrine-dbal', [
            'queryBuilder' => $queryBuilder,
            'countField' => 'u.id',
            'indexField' => 'id'
        ], $this->getId());
        $datasource->setMaxResults(10);

        $datasource->addField('username', 'text', 'like', [
            'field' => 'u.username',
            'form_filter' => true,
            'sortable' => true
        ]);

        $datasource->addField('createdAt', 'datetime', 'eq', [
            'field' => 'u.createdAt',
            'form_filter' => true,
            'sortable' => true
        ]);

        $datasource->addField('credits', 'number', 'eq', [
            'field' => 'u.credits',
            'form_filter' => true,
            'sortable' => true
        ]);

        return $datasource;
    }

    /**
     * ID will appear in routes:
     * - http://example.com/admin/list/{name}
     * - http://example.com/admin/form/{name}
     * etc.
     *
     * @return string
     */
    public function getId()
    {
        return 'admin_users_dbal';
    }

    /**
     * Class name that represent entity. It might be returned in Symfony2 style:
     * FSiDemoBundle:News
     * or as a full class name
     * \FSi\Bundle\DemoBundle\Entity\News
     *
     * @return string
     */
    public function getClassName()
    {
        return User::class;
    }
}