<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Event\DataSourceEvent;

use AdminPanel\Component\DataSource\Event\DataSourceEvent\DataSourceEventArgs;
use AdminPanel\Component\DataSource\DataSourceInterface;
use AdminPanel\Component\DataSource\DataSourceViewInterface;

/**
 * Event class for DataSource.
 */
class ViewEventArgs extends DataSourceEventArgs
{
    /**
     * @var \AdminPanel\Component\DataSource\DataSourceViewInterface
     */
    private $view;

    /**
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $datasource
     * @param \AdminPanel\Component\DataSource\DataSourceViewInterface $view
     */
    public function __construct(DataSourceInterface $datasource, DataSourceViewInterface $view)
    {
        parent::__construct($datasource);
        $this->view = $view;
    }

    /**
     * @return \AdminPanel\Component\DataSource\DataSourceViewInterface
     */
    public function getView()
    {
        return $this->view;
    }
}
