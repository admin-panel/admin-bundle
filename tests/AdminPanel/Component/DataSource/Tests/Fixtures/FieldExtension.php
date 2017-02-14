<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AdminPanel\Component\DataSource\Field\FieldAbstractExtension;
use AdminPanel\Component\DataSource\Event\FieldEvents;

/**
 * Class to test DoctrineDriver extensions calls.
 */
class FieldExtension extends FieldAbstractExtension implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $calls = [];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FieldEvents::PRE_BIND_PARAMETER => ['preBindParameter', 128],
            FieldEvents::POST_BIND_PARAMETER => ['postBindParameter', 128],
            FieldEvents::POST_BUILD_VIEW => ['postBuildView', 128],
            FieldEvents::POST_GET_PARAMETER => ['postGetParameter', 128],
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
        $this->calls[] = $name;
    }

    /**
     * Loads itself as subscriber.
     *
     * @return array
     */
    public function loadSubscribers()
    {
        return [$this];
    }
}
