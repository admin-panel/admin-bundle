<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Ordering\EventSubscriber;

use AdminPanel\Component\DataSource\Event\DataSourceEvent\ParametersEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AdminPanel\Component\DataSource\Event\DataSourceEvents;
use AdminPanel\Component\DataSource\Exception\DataSourceException;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

/**
 * Class contains method called during DataSource events.
 */
class Events implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $ordering = [];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            DataSourceEvents::PRE_BIND_PARAMETERS => ['preBindParameters'],
            DataSourceEvents::POST_GET_PARAMETERS => ['postGetParameters'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function preBindParameters(ParametersEventArgs $event)
    {
        $datasource = $event->getDataSource();
        $datasource_oid = spl_object_hash($datasource);
        $datasourceName = $datasource->getName();
        $parameters = $event->getParameters();

        if (isset($parameters[$datasourceName][OrderingExtension::PARAMETER_SORT]) && is_array($parameters[$datasourceName][OrderingExtension::PARAMETER_SORT])) {
            $priority = 0;
            foreach ($parameters[$datasourceName][OrderingExtension::PARAMETER_SORT] as $fieldName => $direction) {
                if (!in_array($direction, ['asc', 'desc'])) {
                    throw new DataSourceException(sprintf("Unknown sorting direction %s specified", $direction));
                }
                $field = $datasource->getField($fieldName);
                $fieldExtension = $this->getFieldExtension($field);
                $fieldExtension->setOrdering($field, ['priority' => $priority, 'direction' => $direction]);
                $priority++;
            }
            $this->ordering[$datasource_oid] = $parameters[$datasourceName][OrderingExtension::PARAMETER_SORT];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postGetParameters(ParametersEventArgs $event)
    {
        $datasource = $event->getDataSource();
        $datasource_oid = spl_object_hash($datasource);
        $datasourceName = $datasource->getName();
        $parameters = $event->getParameters();

        if (isset($this->ordering[$datasource_oid])) {
            $parameters[$datasourceName][OrderingExtension::PARAMETER_SORT] = $this->ordering[$datasource_oid];
        }

        $event->setParameters($parameters);
    }

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     * @return \AdminPanel\Component\DataSource\Field\FieldExtensionInterface
     * @throws \AdminPanel\Component\DataSource\Exception\DataSourceException
     */
    protected function getFieldExtension(FieldTypeInterface $field)
    {
        $extensions = $field->getExtensions();
        foreach ($extensions as $extension) {
            if ($extension instanceof FieldExtension) {
                return $extension;
            }
        }
        throw new DataSourceException('In order to use ' . __CLASS__ . ' there must be AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension registered in all fields');
    }
}
