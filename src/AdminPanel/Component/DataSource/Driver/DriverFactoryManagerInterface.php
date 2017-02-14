<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver;

interface DriverFactoryManagerInterface
{
    /**
     * @param DriverFactoryInterface $factory
     */
    public function addFactory(DriverFactoryInterface $factory);

    /**
     * @param string $driverType
     * @return null|DriverFactoryInterface
     */
    public function getFactory($driverType);

    /**
     * @param string $driverType
     * @return bool
     */
    public function hasFactory($driverType);
}
