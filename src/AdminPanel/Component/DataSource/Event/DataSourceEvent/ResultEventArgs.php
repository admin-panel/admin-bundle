<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\DataSourceEvent;

use AdminPanel\Component\DataSource\Event\DataSourceEvent\DataSourceEventArgs;
use AdminPanel\Component\DataSource\DataSourceInterface;

/**
 * Event class for DataSource.
 */
class ResultEventArgs extends DataSourceEventArgs
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $datasource
     * @param mixed $result
     */
    public function __construct(DataSourceInterface $datasource, $result)
    {
        parent::__construct($datasource);
        $this->setResult($result);
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
