<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Ordering\Driver\DBAL;

use AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\DoctrineField;
use AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\AbstractField;
use AdminPanel\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\Driver\DriverExtension;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\DBAL\FieldExtension;

class DoctrineExtension extends DriverExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes() : array
    {
        return [
            'doctrine-dbal',
        ];
    }

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
     * {@inheritdoc}
     */
    public function preGetResult(\AdminPanel\Component\DataSource\Event\DriverEvent\DriverEventArgs $event)
    {
        $fields = $event->getFields();
        $sortedFields = $this->sortFields($fields);

        if (count($sortedFields) === 0) {
            return;
        }

        $driver = $event->getDriver();

        $qb = $driver->getQueryBuilder();
        $qb->resetQueryPart('orderBy');

        foreach ($sortedFields as $fieldName => $direction) {
            $field = $fields[$fieldName];
            $qb->addOrderBy($this->getFieldName($field), $direction);
        }
    }

    /**
     * @param \AdminPanel\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\AbstractField $field
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getFieldName(AbstractField $field) : string
    {
        if (!$field instanceof DoctrineField) {
            throw new \InvalidArgumentException("Field must be an instance of DoctrineField");
        }

        if ($field->hasOption('field')) {
            $name = $field->getOption('field');
        } else {
            $name = $field->getName();
        }

        return $name;
    }
}
