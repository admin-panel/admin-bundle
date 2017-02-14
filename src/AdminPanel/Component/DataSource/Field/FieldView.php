<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Field;

use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\DataSourceViewInterface;
use AdminPanel\Component\DataSource\Util\AttributesContainer;
use AdminPanel\Component\DataSource\Field\FieldViewInterface;

/**
 * {@inheritdoc}
 */
class FieldView extends AttributesContainer implements FieldViewInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $comparison;

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var \AdminPanel\Component\DataSource\DataSourceViewInterface
     */
    private $dataSourceView;

    /**
     * {@inheritdoc}
     */
    public function __construct(FieldTypeInterface $field)
    {
        $this->name = $field->getName();
        $this->type = $field->getType();
        $this->comparison = $field->getComparison();
        $this->parameter = $field->getCleanParameter();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSourceView(DataSourceViewInterface $dataSourceView)
    {
        $this->dataSourceView = $dataSourceView;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSourceView()
    {
        return $this->dataSourceView;
    }
}
