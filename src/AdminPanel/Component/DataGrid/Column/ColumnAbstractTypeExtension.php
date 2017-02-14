<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use AdminPanel\Component\DataGrid\DataGridInterface;

abstract class ColumnAbstractTypeExtension implements ColumnTypeExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDataGrid(DataGridInterface $dataGrid)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bindData(ColumnTypeInterface $column, $data, $object, $index)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildCellView(ColumnTypeInterface $column, \AdminPanel\Component\DataGrid\Column\CellViewInterface $view)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeaderView(ColumnTypeInterface $column, \AdminPanel\Component\DataGrid\Column\HeaderViewInterface $view)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue(ColumnTypeInterface $column, $value)
    {
        return $value;
    }
}
