<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core;

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
        return ['doctrine-orm'];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypes() : array
    {
        return [
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Text(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Number(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Date(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Time(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\DateTime(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Entity(),
            new \AdminPanel\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Boolean(),
        ];
    }
}
