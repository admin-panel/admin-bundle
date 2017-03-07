<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core;

use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Boolean;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Date;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\DateTime;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Entity;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Number;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Text;
use AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Time;
use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;

/**
 * Core extension for Doctrine driver.
 * @deprecated since version 1.2
 */
class CoreExtension extends DriverAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes() : array
    {
        return ['doctrine'];
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
            new Entity(),
            new Boolean(),
        ];
    }
}
