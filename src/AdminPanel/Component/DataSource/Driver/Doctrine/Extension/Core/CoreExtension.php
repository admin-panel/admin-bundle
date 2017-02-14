<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core;

use AdminPanel\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

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
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Text(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Number(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Date(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Time(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\DateTime(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Entity(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\Extension\Core\Field\Boolean(),
        ];
    }
}
