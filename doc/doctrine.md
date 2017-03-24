# Doctrine
We supporting typically doctirne entities with getters and setters

### 1. Create element with doctrine

Example:
```php
<?php

// src/AppBundle/Admin/UserElement.php

namespace AppBundle\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListBatchDeleteElement;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use AdminPanel\Component\DataSource\DataSourceInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use AdminPanel\Component\DataGrid\DataGridInterface;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserElement extends GenericListBatchDeleteElement
{
    private $objectManager;

    public function __construct(ObjectManager $manager, array $options = [])
    {
        parent::__construct($options);
        $this->objectManager = $manager;
    }

    /**
     * ID will appear in routes:
     * - http://example.com/admin/list/{name}
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
     * Initialize DataGrid.
     *
     * @param DataGridFactoryInterface $factory
     * @return DataGridInterface
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        // here we adds different elements to our list
        $datagrid = $factory->createDataGrid($this->getId());
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

        // here we adds custom action with our custom class for given element.
        // parameters_field_mapping maps element from our entity to param for given route_name
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
     * @param DataSourceFactoryInterface $factory
     * @return DataSourceInterface
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        // Here we define data source (storage) layer and we can add here filter which will be shown on list
        $datasource = $factory->createDataSource(
            'doctrine',
            ['entity' => $this->getClassName()],
            $this->getClassName()
        );

        // show 10 result per page
        $datasource->setMaxResults(10);

        // allow to sort and filter by username field. Apply like filter to that field
        $datasource->addField(
            'username',
            'text',
            'like',
            [
                'sortable' => true,
                'form_filter' => true
            ]
        );

        // allot to sort and filter by date period
        $datasource->addField(
            'createdAt',
            'datetime',
            'between',
            [
                'sortable' => true,
                'form_filter' => true,
                'form_type'  => DateType::class,
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

        return $datasource;
    }

    /**
     * Handle how we will remove our entity.
     *
     * @param mixed $index - primary key of our entity
     */
    public function delete($index)
    {
        $user = $this
            ->objectManager
            ->getRepository($this->getClassName())
            ->find($index);
        if (!$user) {
            throw new RequestHandlerException('User not found');
        }

        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }
}
```

### 2. Register element

After you create element you have to register using symfony DIC configuration.

Example Element definition in yaml:

```yml
services:
    app.user.admin_element:
        class: AppBundle\Admin\UserElement
        arguments:
            - '@doctrine.orm.default_entity_manager'
        tags:
            - { name: admin.element }
```

Example Element definition in xml:

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="app.user.admin_element" class="AppBundle\Admin\UserElement">
            <argument type="service" id="doctrine.orm.default_entity_manager"/>
            <tag name="admin.element"/>
        </service>
    </services>
</container>
```

