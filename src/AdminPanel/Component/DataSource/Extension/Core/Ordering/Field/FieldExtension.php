<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Ordering\Field;

use AdminPanel\Component\DataSource\Event\FieldEvent\ViewEventArgs;
use AdminPanel\Component\DataSource\Field\FieldAbstractExtension;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use AdminPanel\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use AdminPanel\Component\DataSource\Event\FieldEvents;
use AdminPanel\Component\DataSource\Extension\Core\Pagination\PaginationExtension;

/**
 * Extension for fields.
 */
class FieldExtension extends \AdminPanel\Component\DataSource\Field\FieldAbstractExtension
{
    /**
     * @var array
     */
    private $ordering = [];

    /**
     * {@inheritdoc}
     */
    public function getExtendedFieldTypes()
    {
        return ['text', 'number', 'date', 'time', 'datetime', 'boolean'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FieldEvents::POST_BUILD_VIEW => ['postBuildView']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(\AdminPanel\Component\DataSource\Field\FieldTypeInterface $field)
    {
        $field->getOptionsResolver()
            ->setDefined(['default_sort_priority'])
            ->setDefaults([
                'default_sort' => null,
                'sortable' => true
            ])
            ->setAllowedTypes('default_sort_priority', 'integer')
            ->setAllowedTypes('sortable', 'bool')
            ->setAllowedValues('default_sort', [null, 'asc', 'desc']);
        ;
    }

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     * @param string $ordering
     */
    public function setOrdering(\AdminPanel\Component\DataSource\Field\FieldTypeInterface $field, $ordering)
    {
        $field_oid = spl_object_hash($field);
        $this->ordering[$field_oid] = $ordering;
    }

    /**
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     * @return string|null
     */
    public function getOrdering(\AdminPanel\Component\DataSource\Field\FieldTypeInterface $field)
    {
        $field_oid = spl_object_hash($field);
        if (isset($this->ordering[$field_oid])) {
            return $this->ordering[$field_oid];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postBuildView(ViewEventArgs $event)
    {
        $field = $event->getField();
        $field_oid = spl_object_hash($field);
        $view = $event->getView();

        $view->setAttribute('sortable', $field->getOption('sortable'));
        if (!$field->getOption('sortable')) {
            return;
        }

        $parameters = $field->getDataSource()->getParameters();
        $dataSourceName = $field->getDataSource()->getName();

        if (isset($this->ordering[$field_oid]['direction']) && (key($parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT]) == $field->getName())) {
            $view->setAttribute('sorted_ascending', $this->ordering[$field_oid]['direction'] == 'asc');
            $view->setAttribute('sorted_descending', $this->ordering[$field_oid]['direction'] == 'desc');
        } else {
            $view->setAttribute('sorted_ascending', false);
            $view->setAttribute('sorted_descending', false);
        }

        if (isset($parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT][$field->getName()])) {
            unset($parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT][$field->getName()]);
        }
        if (!isset($parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT])) {
            $parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT] = [];
        }
        // Little hack: we do not know if PaginationExtension is loaded but if it is we don't want page number in sorting URLs.
        unset($parameters[$dataSourceName][PaginationExtension::PARAMETER_PAGE]);
        $fields = array_keys($parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT]);
        array_unshift($fields, $field->getName());
        $directions = array_values($parameters[$dataSourceName][OrderingExtension::PARAMETER_SORT]);

        $parametersAsc = $parameters;
        $directionsAsc = $directions;
        array_unshift($directionsAsc, 'asc');
        $parametersAsc[$dataSourceName][OrderingExtension::PARAMETER_SORT] = array_combine($fields, $directionsAsc);
        $view->setAttribute('parameters_sort_ascending', $parametersAsc);

        $parametersDesc = $parameters;
        $directionsDesc = $directions;
        array_unshift($directionsDesc, 'desc');
        $parametersDesc[$dataSourceName][OrderingExtension::PARAMETER_SORT] = array_combine($fields, $directionsDesc);
        $view->setAttribute('parameters_sort_descending', $parametersDesc);
    }
}
