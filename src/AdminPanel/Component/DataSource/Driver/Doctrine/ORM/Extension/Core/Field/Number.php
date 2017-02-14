<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField;

/**
 * Number field.
 */
class Number extends DoctrineAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = ['eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'between', 'isNull'];

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'number';
    }
}
