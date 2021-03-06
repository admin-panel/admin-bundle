<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Field;

use AdminPanel\Component\DataSource\Field\FieldTypeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extension of DataSources field.
 */
interface FieldExtensionInterface extends EventSubscriberInterface
{
    /**
     * Returns array of extended types.
     *
     * @return array
     */
    public function getExtendedFieldTypes();

    /**
     * Allows extension to load options' constraints to fields OptionsResolver. Called by field.
     *
     * @param \AdminPanel\Component\DataSource\Field\FieldTypeInterface $field
     */
    public function initOptions(FieldTypeInterface $field);
}
