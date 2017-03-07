<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore;

use AdminPanel\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore\Type\FormType;
use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TestCoreExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return [
            new FormType(PropertyAccess::createPropertyAccessor()),
        ];
    }
}
