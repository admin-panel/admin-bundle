<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField;
use Doctrine\DBAL\Types\Type;

/**
 * Boolean field.
 */
class Boolean extends DoctrineAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = ['eq'];

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'boolean';
    }
}
