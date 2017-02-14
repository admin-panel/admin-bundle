<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use AdminPanel\Component\DataGrid\DataGridViewInterface;

interface HeaderViewInterface
{
    /**
     * Set view attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return HeaderViewInterface
     */
    public function setAttribute(string $name, $value) : HeaderViewInterface;

    /**
     * Get view attribute.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name);

    /**
     * Check if view attribute exists.
     *
     * @param string $name
     * @return boolean
     */
     public function hasAttribute(string $name) : bool;

    /**
     * Return all view attributes.
     *
     * @return array
     */
    public function getAttributes() : array;

    /**
     * Get view value. In most cases it should be simple string.
     *
     * @return mixed
     */
    public function getLabel();

    /**
     * Set view value.
     *
     * @param mixed $value
     */
    public function setLabel($value);

    /**
     * Get column name.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Get column type.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Set DataGridView.
     *
     * @param \AdminPanel\Component\DataGrid\DataGridViewInterface $dataGrid
     * @return mixed
     */
    public function setDataGridView(DataGridViewInterface $dataGrid) : HeaderViewInterface;

    /**
     * Get DataGridView.
     *
     * @return \AdminPanel\Component\DataGrid\DataGridViewInterface
     */
    public function getDataGridView() : DataGridViewInterface;
}
