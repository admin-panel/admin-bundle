<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineDriver as BaseDriver;

/**
 * @deprecated since version 1.2
 */
class DoctrineDriver extends BaseDriver
{
    public function __construct($extensions, EntityManager $em, $entity, $alias = null)
    {
        try {
            parent::__construct($extensions, $em, $entity, $alias);
        } catch (DoctrineDriverException $e) {
            throw new \AdminPanel\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'doctrine';
    }

    /**
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \Countable|Paginator
     * @throws \AdminPanel\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException
     */
    public function buildResult($fields, $first, $max)
    {
        try {
            return parent::buildResult($fields, $first, $max);
        } catch (DoctrineDriverException $e) {
            throw new \AdminPanel\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \AdminPanel\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException
     */
    public function getQueryBuilder()
    {
        try {
            return parent::getQueryBuilder();
        } catch (DoctrineDriverException $e) {
            throw new \AdminPanel\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }
}
