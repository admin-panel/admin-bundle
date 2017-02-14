<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Ordering;

use AdminPanel\Component\DataSource\DataSourceAbstractExtension;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\Driver\CollectionExtension;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\Driver\DoctrineExtension;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\EventSubscriber\Events;

/**
 * Ordering extension allows to set orderings for fetched data.
 *
 * It also sets proper ordering priority just before fetching data. It's up to driver
 * to 'catch' these priorities and make it work.
 */
class OrderingExtension extends DataSourceAbstractExtension
{
    /**
     * Key for passing data and ordering attribute.
     */
    const PARAMETER_SORT = 'sort';

    /**
     * {@inheritdoc}
     */
    public function loadDriverExtensions()
    {
        return [
            new DoctrineExtension(),
            new CollectionExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new Events(),
        ];
    }
}
