<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\AbstractField;

final class NumberField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = ['eq', 'neq', 'lt', 'lte', 'gt', 'gte'];

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'number';
    }
}
