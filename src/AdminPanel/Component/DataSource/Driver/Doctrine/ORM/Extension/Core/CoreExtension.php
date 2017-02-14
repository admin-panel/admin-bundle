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
            new Field\Text(),
            new Field\Number(),
            new Field\Date(),
            new Field\Time(),
            new Field\DateTime(),
            new Field\Entity(),
            new Field\Boolean(),
        ];
    }
}
