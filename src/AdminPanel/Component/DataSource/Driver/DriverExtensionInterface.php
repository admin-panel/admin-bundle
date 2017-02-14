<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver;

use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

/**
 * Extension of driver.
 */
interface DriverExtensionInterface
{
    /**
     * Returns types of extended drivers
     *
     * return array|string[]
     */
    public function getExtendedDriverTypes() : array;

    /**
     * Checks whether given extension has field for given type.
     *
     * @param string $type
     * @return bool
     */
    public function hasFieldType(string $type) : bool;

    /**
     * Returns field for given type, or, if can't fine one, throws exception.
     *
     * @param string $type
     * @return \AdminPanel\Component\DataSource\Field\FieldTypeInterface
     */
    public function getFieldType(string $type) : FieldTypeInterface;

    /**
     * Checks whether given extension has any extension for given field type.
     *
     * @param string $type
     * @return bool
     */
    public function hasFieldTypeExtensions(string $type) : bool;

    /**
     * Returns collection of extensions for given field type.
     *
     * @param string $type
     * @return \Traversable
     */
    public function getFieldTypeExtensions(string $type);

    /**
     * Loads events subscribers.
     *
     * Each subscriber must implements \Symfony\Component\EventDispatcher\EventSubscriberInterface.
     *
     * @return array
     */
    public function loadSubscribers() : array;
}
