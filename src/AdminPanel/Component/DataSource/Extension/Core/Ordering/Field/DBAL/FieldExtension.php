<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\DBAL;

use AdminPanel\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension as BaseFieldExtension;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

class FieldExtension extends BaseFieldExtension
{
    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
        parent::initOptions($field);

        $field->getOptionsResolver()
            ->setDefaults([
                'sortable' => false,
            ])
        ;
    }
}
