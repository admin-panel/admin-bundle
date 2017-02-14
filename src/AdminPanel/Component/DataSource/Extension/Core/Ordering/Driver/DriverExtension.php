<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Ordering\Driver;

use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use AdminPanel\Component\DataSource\Event\DriverEvents;

/**
 * Driver extension for ordering that loads fields extension.
 */
abstract class DriverExtension extends DriverAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return [
            new FieldExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() : array
    {
        return [
            DriverEvents::PRE_GET_RESULT => ['preGetResult'],
        ];
    }

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     * @return \AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension|null
     */
    protected function getFieldExtension(FieldTypeInterface $field)
    {
        $extensions = (array) $field->getExtensions();
        foreach ($extensions as $extension) {
            if ($extension instanceof FieldExtension) {
                return $extension;
            }
        }
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function sortFields(array $fields)
    {
        $sortedFields = [];
        $orderingDirection = [];

        $tmpFields = [];
        foreach ($fields as $field) {
            if ($fieldExtension = $this->getFieldExtension($field)) {
                $fieldOrdering = $fieldExtension->getOrdering($field);
                if (isset($fieldOrdering)) {
                    $tmpFields[$fieldOrdering['priority']] = $field;
                    $orderingDirection[$field->getName()] = $fieldOrdering['direction'];
                }
            }
        }
        ksort($tmpFields);
        foreach ($tmpFields as $field) {
            $sortedFields[$field->getName()] = $orderingDirection[$field->getName()];
        }

        $tmpFields = $fields;
        usort($tmpFields, function (FieldTypeInterface $a, FieldTypeInterface $b) {
            switch (true) {
                case $a->hasOption('default_sort') && !$b->hasOption('default_sort'):
                    return -1;

                case !$a->hasOption('default_sort') && $b->hasOption('default_sort'):
                    return 1;

                case $a->hasOption('default_sort') && $b->hasOption('default_sort'):
                    switch (true) {
                        case $a->hasOption('default_sort_priority') && !$b->hasOption('default_sort_priority'):
                            return -1;
                        case !$a->hasOption('default_sort_priority') && $b->hasOption('default_sort_priority'):
                            return 1;
                        case $a->hasOption('default_sort_priority') && $b->hasOption('default_sort_priority'):
                            $aPriority = $a->getOption('default_sort_priority');
                            $bPriority = $b->getOption('default_sort_priority');
                            return ($aPriority != $bPriority) ? (($aPriority > $bPriority) ? -1 : 1) : 0;
                    }

                default:
                    return 0;
            }
        });

        foreach ($tmpFields as $field) {
            if ($field->hasOption('default_sort') && !isset($sortedFields[$field->getName()])) {
                $sortedFields[$field->getName()] = $field->getOption('default_sort');
            }
        }

        return $sortedFields;
    }
}
