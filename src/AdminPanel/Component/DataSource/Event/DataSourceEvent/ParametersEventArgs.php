<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\DataSourceEvent;

use AdminPanel\Component\DataSource\Event\DataSourceEvent\DataSourceEventArgs;
use AdminPanel\Component\DataSource\DataSourceInterface;

/**
 * Event class for DataSource.
 */
class ParametersEventArgs extends DataSourceEventArgs
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $datasource
     * @param array $parameters
     */
    public function __construct(DataSourceInterface $datasource, $parameters)
    {
        parent::__construct($datasource);
        $this->setParameters($parameters);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}
