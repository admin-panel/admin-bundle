<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field;

use AdminPanel\Component\DataSource\Driver\Collection\CollectionAbstractField;
use Doctrine\Common\Collections\Criteria;

/**
 * Boolean field.
 */
class Boolean extends CollectionAbstractField
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

    /**
     * {@inheritdoc}
     */
    public function getPHPType()
    {
        return 'boolean';
    }
}
