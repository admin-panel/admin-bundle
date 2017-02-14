<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use AdminPanel\Component\DataGrid\Column\CellViewInterface;
use AdminPanel\Component\DataGrid\DataGridViewInterface;

class CellView implements CellViewInterface
{
    /**
     * The original object from which the value of the cell was retrieved.
     *
     * @var mixed
     */
    protected $source;

    /**
     * Cell value. In most cases this should be a simple string.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Cell attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Cell name.
     *
     * @var string
     */
    protected $name;

    /**
     * Cell type.
     *
     * @var string
     */
    protected $type;

    /**
     * @var DataGridViewInterface
     */
    protected $datagrid;

    /**
     * @param string $name
     * @param string $type
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(string $name, $value) : CellViewInterface
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute(string $name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttribute(string $name) : bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setSource($source) : CellViewInterface
    {
        $this->source = $source;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGridView(DataGridViewInterface $dataGrid) : CellViewInterface
    {
        $this->datagrid = $dataGrid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataGridView() : DataGridViewInterface
    {
        return $this->datagrid;
    }
}
