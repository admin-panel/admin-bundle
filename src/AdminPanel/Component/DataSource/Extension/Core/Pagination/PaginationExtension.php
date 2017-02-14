<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Pagination;

use AdminPanel\Component\DataSource\DataSourceAbstractExtension;
use FSi\Component\DataSource\Extension\Core\Pagination\EventSubscriber;

/**
 * Pagination extension adds to view some options helpfull during view rendering.
 */
class PaginationExtension extends DataSourceAbstractExtension
{
    /**
     * Key for page info.
     */
    const PARAMETER_PAGE = 'page';

    /**
     * Key for results per page.
     */
    const PARAMETER_MAX_RESULTS = 'max_results';

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new \AdminPanel\Component\DataSource\Extension\Core\Pagination\EventSubscriber\Events(),
        ];
    }
}
