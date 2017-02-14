<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use AdminPanel\Component\DataGrid\Column\CellViewInterface;
use AdminPanel\Component\DataGrid\DataGridInterface;
use AdminPanel\Component\DataGrid\Column\ColumnTypeInterface;
use AdminPanel\Component\DataGrid\Column\HeaderViewInterface;

interface ColumnTypeExtensionInterface
{
    /**
     * @param \AdminPanel\Component\DataGrid\DataGridInterface $dataGrid
     * @return mixed
     *
     * @deprecated This method is deprecated since 1.2 because it is never called
     */
    public function setDataGrid(DataGridInterface $dataGrid);

    /**
     * @param \AdminPanel\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param mixed $data
     * @param mixed $object
     * @param string $index
     */
    public function bindData(ColumnTypeInterface $column, $data, $object, $index);

    /**
     * @param \AdminPanel\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \AdminPanel\Component\DataGrid\Column\CellViewInterface $view
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view);

    /**
     * @param \AdminPanel\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \AdminPanel\Component\DataGrid\Column\HeaderViewInterface $view
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view);

    /**
     * @param \AdminPanel\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param mixed $value
     * @return mixed
     */
    public function filterValue(ColumnTypeInterface $column, $value);

    /**
     * Sets the default options for this type.
     *
     * @param \AdminPanel\Component\DataGrid\Column\ColumnTypeInterface $column
     */
    public function initOptions(ColumnTypeInterface $column);

    /**
     * Return array with extended column types.
     * Example return:
     *
     * return array(
     *     'text',
     *     'date_time'
     * );
     *
     * Extensions will be loaded into columns text and date_time.
     *
     * @return array
     */
    public function getExtendedColumnTypes();
}
