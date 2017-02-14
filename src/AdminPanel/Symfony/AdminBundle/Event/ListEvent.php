<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Event;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Component\DataGrid\DataGridInterface;
use AdminPanel\Component\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class ListEvent extends AdminEvent
{
    /**
     * @var \AdminPanel\Component\DataSource\DataSourceInterface
     */
    protected $dataSource;

    /**
     * @var \AdminPanel\Component\DataGrid\DataGridInterface
     */
    protected $dataGrid;

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $dataSource
     * @param \AdminPanel\Component\DataGrid\DataGridInterface $dataGrid
     */
    public function __construct(Element $element, Request $request, DataSourceInterface $dataSource, DataGridInterface $dataGrid)
    {
        parent::__construct($element, $request);
        $this->dataSource = $dataSource;
        $this->dataGrid = $dataGrid;
    }

    /**
     * @return \AdminPanel\Component\DataSource\DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @return \AdminPanel\Component\DataGrid\DataGridInterface
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }
}
