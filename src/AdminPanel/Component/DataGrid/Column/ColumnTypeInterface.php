<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminPanel\Component\DataGrid\DataGridInterface;
use AdminPanel\Component\DataGrid\Column\CellViewInterface;
use AdminPanel\Component\DataGrid\Column\HeaderViewInterface;
use AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface;
use AdminPanel\Component\DataGrid\Column\ColumnTypeExtensionInterface;

interface ColumnTypeInterface
{
    /**
     * Get column type identity.
     *
     * @return string
     */
    public function getId();

    /**
     * Get name under column is registered in data grid.
     *
     * @return string
     */
    public function getName();

    /**
     * @param \AdminPanel\Component\DataGrid\DataGridInterface $dataGrid
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setDataGrid(DataGridInterface $dataGrid);

    /**
     * @return \AdminPanel\Component\DataGrid\DataGridInterface $dataGrid
     */
    public function getDataGrid();

    /**
     * @param \AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface $dataMapper
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setDataMapper(DataMapperInterface $dataMapper);

    /**
     * Return DataMapper.
     *
     * @return \AdminPanel\Component\DataGrid\DataMapper\DataMapperInterface
     */
    public function getDataMapper();

    /**
     * Filter value before passing it to view.
     *
     * @param mixed $value
     */
    public function filterValue($value);

    /**
     * Get value from object using DataMapper
     *
     * @param mixed $value
     */
    public function getValue($object);

    /**
     * Create CellView object set source value on it.
     *
     * @param mixed  $object
     * @param string $index
     * @return \AdminPanel\Component\DataGrid\Column\CellViewInterface
     * @throws \AdminPanel\Component\DataGrid\Exception\UnexpectedTypeException
     */
    public function createCellView($object, $index);

    /**
     * @param \AdminPanel\Component\DataGrid\Column\CellViewInterface $view
     */
    public function buildCellView(\AdminPanel\Component\DataGrid\Column\CellViewInterface $view);

    /**
     * Create HeaderView object for column.
     *
     * @return \AdminPanel\Component\DataGrid\Column\HeaderViewInterface
     */
    public function createHeaderView();

    /**
     * @param \AdminPanel\Component\DataGrid\Column\HeaderViewInterface $view
     */
    public function buildHeaderView(HeaderViewInterface $view);

    /**
     * Binds data into object using DataMapper object.
     *
     * @param mixed $data
     * @param mixed $object
     * @param mixed $index
     */
    public function bindData($data, $object, $index);

    /**
     * Sets the default options for this type.
     * To access OptionsResolver use $this->getOptionsResolver()
     * initOptions is called in DataGrid after loading the column type
     * from DataGridFactory.
     */
    public function initOptions();

    /**
     * @param string $name
     * @param mixed $value
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setOption($name, $value);

    /**
     * @param array $options
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function setOptions($options);

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * @param string $name
     * @return boolean
     */
    public function hasOption($name);

    /**
     * @param array $extensions
     * @return mixed
     */
    public function setExtensions(array $extensions);

    /**
     * @param \AdminPanel\Component\DataGrid\Column\ColumnTypeExtensionInterface $extension
     * @return \AdminPanel\Component\DataGrid\Column\ColumnTypeExtensionInterface
     */
    public function addExtension(ColumnTypeExtensionInterface $extension);

    /**
     * @return array
     */
    public function getExtensions();

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return \Symfony\Component\OptionsResolver\OptionsResolver The options resolver.
     */
    public function getOptionsResolver();
}
