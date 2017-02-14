<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Field;

use AdminPanel\Component\DataSource\Field\FieldExtensionInterface;
use AdminPanel\Component\DataSource\Field\FieldTypeInterface;

/**
 * {@inheritdoc}
 */
class FieldAbstractExtension implements FieldExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedFieldTypes()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
    }
}
