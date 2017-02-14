<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Collection\CollectionAbstractField;

/**
 * Text field.
 */
class Text extends CollectionAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = ['eq', 'neq', 'in', 'nin', 'contains'];

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'text';
    }
}
