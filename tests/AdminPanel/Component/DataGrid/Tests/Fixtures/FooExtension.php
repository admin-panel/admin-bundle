<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests\Fixtures;

use AdminPanel\Component\DataGrid\DataGridAbstractExtension;

class FooExtension extends DataGridAbstractExtension
{
    protected function loadColumnTypes()
    {
        return [
            new \AdminPanel\Component\DataGrid\Tests\Fixtures\ColumnType\FooType(),
        ];
    }
}
