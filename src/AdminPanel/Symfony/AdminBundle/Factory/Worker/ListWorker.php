<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Factory\Worker;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Factory\Worker;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;

class ListWorker implements Worker
{
    /**
     * @var \AdminPanel\Component\DataSource\DataSourceFactoryInterface
     */
    private $dataSourceFactory;

    /**
     * @var \AdminPanel\Component\DataGrid\DataGridFactoryInterface
     */
    private $dataGridFactory;

    /**
     * @param DataSourceFactoryInterface $dataSourceFactory
     */
    public function __construct(
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $this->dataSourceFactory = $dataSourceFactory;
        $this->dataGridFactory = $dataGridFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof ListElement) {
            $element->setDataSourceFactory($this->dataSourceFactory);
            $element->setDataGridFactory($this->dataGridFactory);
            return;
        }
        if ($element instanceof DataSourceAwareInterface) {
            $element->setDataSourceFactory($this->dataSourceFactory);
        }
        if ($element instanceof DataGridAwareInterface) {
            $element->setDataGridFactory($this->dataGridFactory);
        }
    }
}
