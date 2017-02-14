<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\EventSubscriber;

use Doctrine\ORM\Tools\Pagination\Paginator;
use AdminPanel\Component\DataSource\Driver\Doctrine\ORM\DoctrineResult;
use AdminPanel\Component\DataSource\Event\DriverEvent\ResultEventArgs;
use AdminPanel\Component\DataSource\Event\DriverEvents;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class contains method called at BindParameters events.
 */
class ResultIndexer implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    protected $registry;

    /**
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [DriverEvents::POST_GET_RESULT => ['postGetResult', 1024]];
    }

    /**
     * @param \AdminPanel\Component\DataSource\Event\DriverEvent\ResultEventArgs $event
     */
    public function postGetResult(ResultEventArgs $event)
    {
        $result = $event->getResult();

        if ($result instanceof Paginator) {
            $result = new DoctrineResult($this->registry, $result);
            $event->setResult($result);
        }
    }
}
