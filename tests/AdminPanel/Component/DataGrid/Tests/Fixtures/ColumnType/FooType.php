<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataGrid\Tests\Fixtures\ColumnType;

use AdminPanel\Component\DataGrid\Column\ColumnAbstractType;

class FooType extends ColumnAbstractType
{
    public function getId()
    {
        return 'foo';
    }

    public function filterValue($value)
    {
        return $value;
    }
}
