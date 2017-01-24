<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

class Batch extends ColumnAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'batch';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        return $this->getIndex();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($object)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCellView(CellViewInterface $view)
    {
        $view->setAttribute('datagrid_name', $this->getDataGrid()->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeaderView(HeaderViewInterface $view)
    {
        $view->setAttribute('datagrid_name', $this->getDataGrid()->getName());
    }
}
