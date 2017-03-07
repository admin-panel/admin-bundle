<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Collection\Extension\Core;

use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Boolean;
use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Date;
use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\DateTime;
use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Number;
use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Text;
use AdminPanel\Component\DataSource\Driver\Collection\Extension\Core\Field\Time;
use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;

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
            new Text(),
            new Number(),
            new Date(),
            new Time(),
            new DateTime(),
            new Boolean(),
        ];
    }
}
