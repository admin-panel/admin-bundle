<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine;

use AdminPanel\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException as LegacyDoctrineDriverException;
use Doctrine\ORM\QueryBuilder;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField as BaseField;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;

/**
 * @deprecated since version 1.2
 */
abstract class DoctrineAbstractField extends BaseField implements DoctrineFieldInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LegacyDoctrineDriverException
     */
    public function buildQuery(QueryBuilder $qb, $alias)
    {
        try {
            parent::buildQuery($qb, $alias);
        } catch (DoctrineDriverException $e) {
            throw new LegacyDoctrineDriverException(
                $e->getMessage()
            );
        }
    }
}
