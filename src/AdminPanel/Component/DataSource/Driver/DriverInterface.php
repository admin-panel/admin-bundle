<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver;

use AdminPanel\Component\DataSource\DataSourceInterface;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

/**
 * Driver is responsible for fetching data based on passed fields and data.
 */
interface DriverInterface
{
    /**
     * Returns type (name) of this driver.
     *
     * @return string
     */
    public function getType();

    /**
     * Sets reference to DataSource.
     *
     * @param DataSourceInterface $datasource
     */
    public function setDataSource(DataSourceInterface $datasource);

    /**
     * Return reference to assigned DataSource.
     *
     * @return DataSourceInterface
     */
    public function getDataSource();

    /**
     * Checks if driver has field for given type.
     *
     * @param string $type
     * @return bool
     */
    public function hasFieldType($type);

    /**
     * Return field for given type.
     *
     * @param FieldTypeInterface $type
     */
    public function getFieldType($type);

    /**
     * Returns collection with result.
     *
     * Returned object must implement interfaces Countable and IteratorAggregate. Count on this object must return amount
     * of all available results.
     *
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \Countable, \IteratorAggregate
     */
    public function getResult($fields, $first, $max);

    /**
     * Returns loaded extensions.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Adds extension to driver.
     *
     * @param \AdminPanel\Component\DataSource\Driver\DriverExtensionInterface $extension
     */
    public function addExtension(DriverExtensionInterface $extension);
}
