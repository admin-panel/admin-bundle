<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver;

use AdminPanel\Component\DataSource\Event\DriverEvent\DriverEventArgs;
use AdminPanel\Component\DataSource\Event\DriverEvent\ResultEventArgs;
use AdminPanel\Component\DataSource\Exception\DataSourceException;
use AdminPanel\Component\DataSource\DataSourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use AdminPanel\Component\DataSource\Event\DriverEvents;

/**
 * {@inheritdoc}
 */
abstract class DriverAbstract implements DriverInterface
{
    /**
     * Extensions.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Field types.
     *
     * @var array
     */
    protected $fieldTypes = [];

    /**
     * Fields extensions.
     *
     * @var array
     */
    protected $fieldExtensions = [];

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param $extensions array with extensions
     * @throws \AdminPanel\Component\DataSource\Exception\DataSourceException
     */
    public function __construct(array $extensions = [])
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof DriverExtensionInterface) {
                throw new DataSourceException(sprintf('Instance of DriverExtensionInterface expected, "%s" given.', get_class($extension)));
            }
            $this->addExtension($extension);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSource(DataSourceInterface $datasource)
    {
        $this->datasource = $datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource()
    {
        return $this->datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFieldType($type)
    {
        $this->initFieldType($type);
        return isset($this->fieldTypes[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType($type)
    {
        if (!$this->hasFieldType($type)) {
            throw new DataSourceException(sprintf('Unsupported field type ("%s").', $type));
        }

        $field = clone $this->fieldTypes[$type];
        $field->initOptions();

        if (isset($this->fieldExtensions[$type])) {
            $field->setExtensions($this->fieldExtensions[$type]);
        }

        return $field;
    }

    /**
     * Inits field for given type (including extending that type) and saves it as pattern for later cloning.
     *
     * @param string $type
     */
    private function initFieldType($type)
    {
        if (isset($this->fieldTypes[$type])) {
            return;
        }

        $typeInstance = false;
        foreach ($this->extensions as $extension) {
            if ($extension->hasFieldType($type)) {
                $typeInstance = $extension->getFieldType($type);
                break;
            }
        }

        if (!$typeInstance) {
            return;
        }

        $this->fieldTypes[$type] = $typeInstance;

        $ext = [];
        foreach ($this->extensions as $extension) {
            if ($extension->hasFieldTypeExtensions($type)) {
                $fieldExtensions = $extension->getFieldTypeExtensions($type);
                foreach ($fieldExtensions as $fieldExtension) {
                    $ext[] = $fieldExtension;
                }
            }
        }

        $this->fieldExtensions[$type] = $ext;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtension(DriverExtensionInterface $extension)
    {
        if (!in_array($this->getType(), $extension->getExtendedDriverTypes())) {
            throw new DataSourceException(sprintf('DataSource driver extension of class %s does not support %s driver', get_class($extension), $this->getType()));
        }

        if (in_array($extension, $this->extensions, true)) {
            return;
        }

        $eventDispatcher = $this->getEventDispatcher();
        foreach ($extension->loadSubscribers() as $subscriber) {
            $eventDispatcher->addSubscriber($subscriber);
        }

        $this->extensions[] = $extension;
    }

    /**
     * Returns reference to EventDispatcher.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcher()
    {
        if (!isset($this->eventDispatcher)) {
            $this->eventDispatcher = new EventDispatcher();
        }

        return $this->eventDispatcher;
    }

    /**
     * Initialize building results i.e. prepare DQL query or initial XPath expression object.
     */
    abstract protected function initResult();

    /**
     * Build result that will be returned by getResult.
     *
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \Countable, \IteratorAggregate
     */
    abstract protected function buildResult($fields, $first, $max);

    /**
     * {@inheritdoc}
     */
    public function getResult($fields, $first, $max)
    {
        $this->initResult();

        //preGetResult event.
        $event = new DriverEventArgs($this, $fields);
        $this->getEventDispatcher()->dispatch(DriverEvents::PRE_GET_RESULT, $event);

        $result = $this->buildResult($fields, $first, $max);

        //postGetResult event.
        $event = new ResultEventArgs($this, $fields, $result);
        $this->getEventDispatcher()->dispatch(DriverEvents::POST_GET_RESULT, $event);
        $result = $event->getResult();

        return $result;
    }
}
