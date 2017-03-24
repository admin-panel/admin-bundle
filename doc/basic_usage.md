# Basic usage

After successful installation you can start from register Elements which will be handled by admin bundle.
Element is just object which allow admin panel to handling your website resources.

At this moment we have handle list and batch elements.

This document assumes that you are using typical doctrine entity with getters and setters,
If you want to fetch data from database without any entity please check our [dbal](dbal.md) documentation.

## To generate list of our resources we need do following steps:

### 1. Create element

We will create element which will work with doctrine entity and allow to batch delete all users.
In element you can define data grid fields (fields visible on list) and you can define data source
so place when you decide from where element will fetch data. Please read comments in the example below.

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

Now you should be able to enter `http://example.com/admin/list/admin_users`.

### 3. Adding element to menu

You can add element to menu by adding such configuration into your `app/config/config.yml` file:

```yaml
# app/config/config.yml

admin_panel:
    menu:
        - {"id": "admin_users", "name": "Users"}
```

id is name of the element and name is label which will be shown on the menu.

Bundle allows to add menu items which are not elements with custom routes and allows to create submenu as well.
Example:

```yaml
# app/config/config.yml

admin_panel:
    menu:
        - {"id": "admin_users", "name": "Users"}
        - {"route": "my_route", "name": "Custom"},
        - {
            "name": "Sub menu",
            "children":
              - {"route": "my_other_route", "name": "Other route"}
          }
```
