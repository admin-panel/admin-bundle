<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver;

class DriverFactoryManager implements DriverFactoryManagerInterface
{
    /**
     * @var array
     */
    private $factories;

    /**
     * @param array $factories
     * @throws \InvalidArgumentException
     */
    public function __construct($factories = [])
    {
        $this->factories = [];

        foreach ($factories as $factory) {
            if (!$factory instanceof DriverFactoryInterface) {
                throw new \InvalidArgumentException("Factory must implement \\AdminPanel\\Component\\DataSource\\Driver\\DriverFactoryInterface");
            }

            $this->addFactory($factory);
        }
    }

    /**
     * @param \AdminPanel\Component\DataSource\Driver\DriverFactoryInterface $factory
     */
    public function addFactory(DriverFactoryInterface $factory)
    {
        $this->factories[$factory->getDriverType()] = $factory;
    }

    /**
     * @param string $driverType
     * @return null|\AdminPanel\Component\DataSource\Driver\DriverFactoryInterface
     */
    public function getFactory($driverType)
    {
        if ($this->hasFactory($driverType)) {
            return $this->factories[$driverType];
        }
    }

    /**
     * @param string $driverType
     * @return bool
     */
    public function hasFactory($driverType)
    {
        return array_key_exists($driverType, $this->factories);
    }
}
