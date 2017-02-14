<?php

namespace AdminPanel\Component\DataSource\Tests\Extension\Core;

use AdminPanel\Component\DataSource\Field\FieldAbstractType;

class FakeFieldType extends FieldAbstractType
{
    public function getType()
    {
        return 'fake';
    }
}