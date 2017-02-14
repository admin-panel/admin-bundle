<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource;

/**
 * Factory maintains building new DataSources with preconfigured extensions.
 */
interface DataSourceFactoryInterface
{
    /**
     * Creates instance of data source with given driver and name.
     *
     * @param string $driver
     * @param array $driverOptions
     * @param $name
     * @return \AdminPanel\Component\DataSource\DataSource
     */
    public function createDataSource($driver, $driverOptions = [], $name);

    /**
     * Adds extension to list.
     *
     * @param \AdminPanel\Component\DataSource\DataSourceExtensionInterface $extension
     */
    public function addExtension(DataSourceExtensionInterface $extension);

    /**
     * Return array of loaded extensions.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Return array of all parameters from all datasources.
     *
     * @return array
     */
    public function getAllParameters();

    /**
     * Return array of all parameters form all datasources except given.
     *
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $datasource
     * @return array
     */
    public function getOtherParameters(DataSourceInterface $datasource);

    /**
     * Adds given datasource to list of known datasources, so its data will be fetched
     * during getAllParameters and getOtherParameters.
     *
     * Factory also automatically sets its (datasource) factory to itself.
     *
     * @param \AdminPanel\Component\DataSource\DataSourceInterface $datasource
     */
    public function addDataSource(DataSourceInterface $datasource);
}
