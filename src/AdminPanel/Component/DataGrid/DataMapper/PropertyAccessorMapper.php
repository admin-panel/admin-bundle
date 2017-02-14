<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\DataMapper;

use AdminPanel\Component\DataGrid\Exception\DataMappingException;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyAccessorMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function getData(string $field, $object)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        try {
            $data = $accessor->getValue($object, $field);
        } catch (RuntimeException $e) {
            throw new DataMappingException($e->getMessage());
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(string $field, $object, $value)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        try {
            $accessor->setValue($object, $field, $value);
        } catch (RuntimeException $e) {
            throw new DataMappingException($e->getMessage());
        }
    }
}
