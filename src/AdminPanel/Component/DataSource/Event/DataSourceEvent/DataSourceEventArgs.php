<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\DataSourceEvent;

use Symfony\Component\EventDispatcher\Event;
use AdminPanel\Component\DataSource\DataSourceInterface;

/**
 * Event class for DataSource.
 */
class DataSourceEventArgs extends Event
{
    /**
     * @var \AdminPanel\Component\DataSource\DataSourceInterface
     */
    private $datasource;

    /**
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $datasource
     */
    public function __construct(DataSourceInterface $datasource)
    {
        $this->datasource = $datasource;
    }

    /**
     * @return \AdminPanel\Component\DataSource\DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->datasource;
    }
}
