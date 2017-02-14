<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;

class DataGridAwareElement extends SimpleAdminElement implements DataGridAwareInterface
{
    private $dataGridFactory;

    /**
     * @param \AdminPanel\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->dataGridFactory = $factory;
    }
}
