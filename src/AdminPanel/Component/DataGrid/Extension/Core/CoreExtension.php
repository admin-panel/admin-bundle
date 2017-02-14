<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Extension\Core;

use AdminPanel\Component\DataGrid\DataGridAbstractExtension;

class CoreExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\Text(),
            new ColumnType\Number(),
            new ColumnType\Collection(),
            new ColumnType\DateTime(),
            new ColumnType\Money(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return [
            new ColumnTypeExtension\DefaultColumnOptionsExtension(),
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadSubscribers()
    {
        return [
            new EventSubscriber\ColumnOrder(),
        ];
    }
}
