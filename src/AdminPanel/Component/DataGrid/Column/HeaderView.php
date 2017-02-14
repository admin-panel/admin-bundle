<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Column;

use AdminPanel\Component\DataGrid\DataGridViewInterface;

class HeaderView implements HeaderViewInterface
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var \AdminPanel\Component\DataGrid\DataGridViewInterface
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
    public function setAttribute(string $name, $value) : HeaderViewInterface
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
    public function hasAttribute(string $name) : bool
    {
        return array_key_exists($name, $this->attributes);
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
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
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
    public function setDataGridView(DataGridViewInterface $dataGrid) : HeaderViewInterface
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
