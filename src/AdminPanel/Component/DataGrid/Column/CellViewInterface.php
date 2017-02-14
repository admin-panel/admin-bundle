<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use AdminPanel\Component\DataGrid\DataGridViewInterface;

interface CellViewInterface
{
    /**
     * Check if view attribute exists.
     *
     * @param string $name
     * @return boolean
     */
    public function hasAttribute(string $name) : bool;

    /**
     * Set view attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return CellViewInterface
     */
    public function setAttribute(string $name, $value) : CellViewInterface;

    /**
     * Get view attribute.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name);

    /**
     * Get all cell attributes.
     *
     * @return array
     */
    public function getAttributes() : array;

    /**
     * Set the source object.
     *
     * @param mixed $source
     * @return CellViewInterface
     */
    public function setSource($source) : CellViewInterface;

    /**
     * Get the source object.
     *
     * @return mixed
     */
    public function getSource();

    /**
     * Get view value. In most cases it should be simple string.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set view value.
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * Return cell column type.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Return cell column name.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Set DataGridView.
     *
     * @param DataGridViewInterface $dataGrid
     * @return CellViewInterface
     */
    public function setDataGridView(DataGridViewInterface $dataGrid): CellViewInterface;

    /**
     * Get DataGridView.
     *
     * @return DataGridViewInterface
     */
    public function getDataGridView() : DataGridViewInterface;
}
