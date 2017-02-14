<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;

interface DoctrineField
{
    /**
     * @param QueryBuilder $queryBuilder
     */
    public function buildQuery(QueryBuilder $queryBuilder);
}
