<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Element;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListBatchDeleteElement;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use AdminPanel\Symfony\AdminBundle\Form\Type\BetweenDateType;
use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

final class UserElement extends GenericListBatchDeleteElement
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct();

        $this->objectManager = $objectManager;
    }

    /**
     * Initialize DataGrid.
     *
     * @param \AdminPanel\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \AdminPanel\Component\DataGrid\DataGridInterface
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \AdminPanel\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid(
            'admin_users' // this is the ID of the element's datagrid
        );
        $datagrid->addColumn('username', 'text', [
            'label' => 'Username'
        ]);
        $datagrid->addColumn('hasNewsletter', 'boolean', [
            'label' => 'Has newsletter?'
        ]);
        $datagrid->addColumn('createdAt', 'datetime', [
            'label' => 'Created at'
        ]);
        $datagrid->addColumn('credits', 'money', [
            'label' => 'Credits',
            'currency' => 'EUR'
        ]);
        $datagrid->addColumn('actions', 'action', [
            'label' => 'Actions',
            'field_mapping' => ['id'],
            'actions' => [
                'custom' => [
                    'url_attr' => [
                        'class' => 'btn btn-warning btn-small-horizontal',
                        'title' => 'Custom'
                    ],
                    'route_name' => 'custom_action',
                    'parameters_field_mapping' => [
                        'id' => 'id'
                    ]
                ]
            ]
        ]);

        return $datagrid;
    }

    /**
     * Initialize DataSource.
     *
     * @param \AdminPanel\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \AdminPanel\Component\DataSource\DataSourceInterface
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \AdminPanel\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource(
            'doctrine',
            ['entity' => $this->getClassName()],
            'admin_users' // this is the ID of the element's datasource
        );
        $datasource->setMaxResults(10);
        $datasource->addField(
            'username',
            'text',
            'like',
            [
                'sortable' => true,
                'form_filter' => true
            ]
        );
        $datasource->addField(
            'createdAt',
            'datetime',
            'between',
            [
                'sortable' => true,
                'form_filter' => true,
                'form_type'  => DateTimeType::class,
                'form_from_options' => [
                    'widget' => 'single_text',
                    'input' => 'string',
                ],
                'form_to_options' => [
                    'widget' => 'single_text',
                    'input' => 'string',
                ]
            ]
        );
        $datasource->addField('createdAtDate', 'datetime', 'between', [
            'field' => 'created_at',
            'form_filter' => true,
            'form_type'  => BetweenDateType::class,
            'form_from_options' => [
                'date_format' => 'yyyy-MM-dd 00:00:00',
                'widget' => 'single_text',
                'input' => 'string',
            ],
            'form_to_options' => [
                'date_format' => 'yyyy-MM-dd 23:59:59',
                'widget' => 'single_text',
                'input' => 'string',
            ]
        ]);

        $datasource->addField(
            'credits',
            'number',
            'eq',
            [
                'sortable' => true,
                'form_filter' => true
            ]
        );

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
        return 'admin_users';
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return User::class;
    }

    /**
     * @param mixed $index
     */
    public function delete($index)
    {
        $user = $this->objectManager->getRepository(User::class)->find($index);
        if (!$user) {
            throw new RequestHandlerException('User not found');
        }

        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }
}