<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\FieldEvent;

use AdminPanel\Component\DataSource\Event\FieldEvent\FieldEventArgs;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

/**
 * Event class for Field.
 */
class ParameterEventArgs extends FieldEventArgs
{
    /**
     * @var mixed
     */
    private $parameter;

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     */
    public function __construct(FieldTypeInterface $field, $parameter)
    {
        parent::__construct($field);
        $this->setParameter($parameter);
    }

    /**
     * @param mixed $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @return mixed
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}
