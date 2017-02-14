<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource;

use AdminPanel\Component\DataSource\Util\AttributesContainerInterface;

/**
 * DataSources view is responsible for keeping options needed to build view, fields view objects,
 * and proxy some requests to DataSource.
 */
interface DataSourceViewInterface extends AttributesContainerInterface, \ArrayAccess, \Countable, \SeekableIterator
{
    /**
     * Returns name of datasource.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns parameters that were binded to datasource.
     *
     * @return array
     */
    public function getParameters();

    /**
     * Returns parameters that were binded to all datasources.
     *
     * @return array
     */
    public function getAllParameters();

    /**
     * Returns parameters that were binded to other datasources.
     *
     * @return array
     */
    public function getOtherParameters();

    /**
     * Checks whether view has field with given name.
     *
     * @param string $name
     */
    public function hasField($name);

    /**
     * Removes field with given name.
     *
     * @param string $name
     */
    public function removeField($name);

    /**
     * Returns field with given name.
     *
     * @param string $name
     */
    public function getField($name);

    /**
     * Return array of all fields.
     *
     * @return array
     */
    public function getFields();

    /**
     * Removes all fields.
     *
     * @return array
     */
    public function clearFields();

    /**
     * Adds new field view.
     *
     * @param \AdminPanel\Component\DataSource\Field\FieldViewInterface $fieldView
     */
    public function addField(\AdminPanel\Component\DataSource\Field\FieldViewInterface $fieldView);

    /**
     * Replace fields with specified ones.
     *
     * Each of field must be instance of \AdminPanel\Component\DataSource\Field\FieldViewInterface
     *
     * @param \AdminPanel\Component\DataSource\Field\FieldViewInterface[] $fieldView
     */
    public function setFields(array $fields);

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getResult();
}
