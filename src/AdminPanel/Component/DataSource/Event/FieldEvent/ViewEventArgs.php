<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\FieldEvent;

use AdminPanel\Component\DataSource\Event\FieldEvent\FieldEventArgs;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\Field\FieldViewInterface;

/**
 * Event class for Field.
 */
class ViewEventArgs extends FieldEventArgs
{
    /**
     * @var \AdminPanel\Component\DataSource\Field\FieldViewInterface
     */
    private $view;

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     * @param \AdminPanel\Component\DataSource\Field\FieldViewInterface $view
     */
    public function __construct(FieldTypeInterface $field, FieldViewInterface $view)
    {
        parent::__construct($field);
        $this->view = $view;
    }

    /**
     * @return \AdminPanel\Component\DataSource\Field\FieldViewInterface
     */
    public function getView()
    {
        return $this->view;
    }
}
