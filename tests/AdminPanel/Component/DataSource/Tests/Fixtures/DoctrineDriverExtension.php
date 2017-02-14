<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Fixtures;

use Doctrine\ORM\QueryBuilder;
use FSi\Component\DataSource\Tests\Fixtures\Doctrine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;
use AdminPanel\Component\DataSource\Event\DriverEvents;

/**
 * Class to test DoctrineDriver extensions calls.
 */
class DoctrineDriverExtension extends DriverAbstractExtension implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $calls = [];

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function getExtendedDriverTypes() : array
    {
        return ['doctrine', 'doctrine-orm'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() : array
    {
        return [
            DriverEvents::PRE_GET_RESULT => ['preGetResult', 128],
            DriverEvents::POST_GET_RESULT => ['postGetResult', 128],
        ];
    }

    /**
     * Returns array of calls.
     *
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Resets calls.
     */
    public function resetCalls()
    {
        $this->calls = [];
    }

    /**
     * Catches called method.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if ($name == 'preGetResult') {
            $args = array_shift($arguments);
            $this->queryBuilder = $args->getDriver()->getQueryBuilder();
        }
        $this->calls[] = $name;
    }

    /**
     * Loads itself as subscriber.
     *
     * @return array
     */
    public function loadSubscribers() : array
    {
        return [$this];
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder() : QueryBuilder
    {
        return $this->queryBuilder;
    }
}
