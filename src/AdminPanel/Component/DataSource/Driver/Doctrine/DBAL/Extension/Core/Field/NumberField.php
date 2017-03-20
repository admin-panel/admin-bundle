<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field;

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
