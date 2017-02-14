<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Collection\Extension\Core;

use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Collection\Extension\Core\Field;

/**
 * Core extension for Doctrine driver.
 */
class CoreExtension extends DriverAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes() : array
    {
        return ['collection'];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypes() : array
    {
        return [
            new \AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Text(),
            new \AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Number(),
            new \AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Date(),
            new \AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Time(),
            new \AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\DateTime(),
            new \AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Boolean(),
        ];
    }
}
