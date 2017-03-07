<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Symfony\Core\EventSubscriber;

use AdminPanel\Component\DataSource\Event\DataSourceEvent\ParametersEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AdminPanel\Component\DataSource\Event\DataSourceEvents;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class contains method called at BindParameters events.
 */
class BindParameters implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [DataSourceEvents::PRE_BIND_PARAMETERS => ['preBindParameters', 1024]];
    }

    /**
     * Method called at PreBindParameters event.
     *
     * @param ParametersEventArgs $event
     */
    public function preBindParameters(ParametersEventArgs $event)
    {
        $parameters = $event->getParameters();
        if ($parameters instanceof Request) {
            $event->setParameters($parameters->query->all());
        }
    }
}
