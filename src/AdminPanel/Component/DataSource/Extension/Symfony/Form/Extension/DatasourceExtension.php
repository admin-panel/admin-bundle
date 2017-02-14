<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Extension\Symfony\Form\Extension;

use AdminPanel\Component\DataSource\Extension\Symfony\Form\Type\BetweenType;
use Symfony\Component\Form\AbstractExtension;

class DatasourceExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return [new BetweenType()];
    }
}
