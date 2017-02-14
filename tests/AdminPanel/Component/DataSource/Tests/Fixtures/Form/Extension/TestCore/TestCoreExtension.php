<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FSi\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore\Type;

class TestCoreExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return [
            new \AdminPanel\Component\DataSource\Tests\Fixtures\Form\Extension\TestCore\Type\FormType(PropertyAccess::createPropertyAccessor()),
        ];
    }
}
