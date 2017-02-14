<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\FieldEvent;

use Symfony\Component\EventDispatcher\Event;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

/**
 * Event class for Field.
 */
class FieldEventArgs extends Event
{
    /**
     * @var \AdminPanel\Component\DataSource\Field\FieldTypeInterface
     */
    private $field;

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     */
    public function __construct(FieldTypeInterface $field)
    {
        $this->field = $field;
    }

    /**
     * @return \AdminPanel\Component\DataSource\Field\FieldTypeInterface
     */
    public function getField()
    {
        return $this->field;
    }
}
