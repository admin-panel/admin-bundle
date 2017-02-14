<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Extension\Doctrine;

use AdminPanel\Component\DataGrid\DataGridAbstractExtension;

class DoctrineExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\Entity(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return [
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        ];
    }
}
