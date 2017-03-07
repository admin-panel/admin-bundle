<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;

class DataSourceAwareElement extends SimpleAdminElement implements DataSourceAwareInterface
{
    private $dataSourceFactory;

    /**
     * @param DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->dataSourceFactory = $factory;
    }
}
