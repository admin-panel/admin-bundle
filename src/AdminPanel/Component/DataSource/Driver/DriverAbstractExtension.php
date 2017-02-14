<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AdminPanel\Component\DataSource\Exception\DataSourceException;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\Field\FieldExtensionInterface;

/**
 * {@inheritdoc}
 */
abstract class DriverAbstractExtension implements DriverExtensionInterface, EventSubscriberInterface
{
    /**
     * Array of fields types.
     *
     * @var array
     */
    private $fieldTypes;

    /**
     * Array of fields extensions.
     *
     * @var array
     */
    private $fieldTypesExtensions;

    /**
     * {@inheritdoc}
     */
    public function hasFieldType(string $type) : bool
    {
        if (!isset($this->fieldTypes)) {
            $this->initFieldsTypes();
        }

        return isset($this->fieldTypes[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType(string $type) : FieldTypeInterface
    {
        if (!isset($this->fieldTypes)) {
            $this->initFieldsTypes();
        }

        if (!isset($this->fieldTypes[$type])) {
            throw new DataSourceException(sprintf('Field with type "%s" can\'t be loaded.', $type));
        }

        return $this->fieldTypes[$type];
    }

    /**
     * {@inheritdoc}
     */
    public function hasFieldTypeExtensions(string $type) : bool
    {
        if (!isset($this->fieldTypesExtensions)) {
            $this->initFieldTypesExtensions();
        }

        return isset($this->fieldTypesExtensions[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldTypeExtensions(string $type)
    {
        if (!isset($this->fieldTypesExtensions)) {
            $this->initFieldTypesExtensions();
        }

        if (!isset($this->fieldTypesExtensions[$type])) {
            throw new DataSourceException(sprintf('Field extensions with type "%s" can\'t be loaded.', $type));
        }

        return $this->fieldTypesExtensions[$type];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypes()
    {
        return [];
    }

    /**
     * Initializes every field type in extension.
     *
     * @throws \AdminPanel\Component\DataSource\Exception\DataSourceException
     */
    private function initFieldsTypes()
    {
        $this->fieldTypes = [];

        $fieldTypes = $this->loadFieldTypes();

        foreach ($fieldTypes as $fieldType) {
            if (!$fieldType instanceof FieldTypeInterface) {
                throw new DataSourceException(sprintf('Expected instance of FieldTypeInterface, "%s" given.', get_class($fieldType)));
            }

            if (isset($this->fieldTypes[$fieldType->getType()])) {
                throw new DataSourceException(sprintf('Error during field types loading. Name "%s" already in use.', $fieldType->getType()));
            }

            $this->fieldTypes[$fieldType->getType()] = $fieldType;
        }
    }

    /**
     * Initializes every field extension if extension.
     *
     * @throws \AdminPanel\Component\DataSource\Exception\DataSourceException
     */
    private function initFieldTypesExtensions()
    {
        $fieldTypesExtensions = $this->loadFieldTypesExtensions();
        foreach ($fieldTypesExtensions as $extension) {
            if (!$extension instanceof FieldExtensionInterface) {
                throw new DataSourceException(sprintf("Expected instance of AdminPanel\\Component\\DataSource\\Field\\FieldExtensionInterface but %s got", get_class($extension)));
            }

            $types = $extension->getExtendedFieldTypes();
            foreach ($types as $type) {
                if (!isset($this->fieldTypesExtensions)) {
                    $this->fieldTypesExtensions[$type] = [];
                }
                $this->fieldTypesExtensions[$type][] = $extension;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers() : array
    {
        return [$this];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() : array
    {
        return [];
    }
}
