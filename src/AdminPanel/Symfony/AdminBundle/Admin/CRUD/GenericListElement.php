<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\AbstractElement;
use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericListElement extends AbstractElement implements ListElement, DeleteElement
{
    /**
     * @var \FSi\Component\DataSource\DataSourceFactoryInterface
     */
    protected $datasourceFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_list';
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return $this->getRouteParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return $this->getRoute();
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->datagridFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->datasourceFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataGrid()
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

        if (!is_object($datagrid) || !$datagrid instanceof DataGridInterface) {
            throw new RuntimeException('initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface');
        }

        if ($this->options['allow_delete']) {
            if (!$datagrid->hasColumnType('batch')) {
                $datagrid->addColumn('batch', 'batch', [
                    'actions' => [
                        'delete' => [
                            'route_name' => 'fsi_admin_batch',
                            'additional_parameters' => ['element' => $this->getId()],
                            'label' => 'crud.list.batch.delete'
                        ]
                    ],
                    'display_order' => -1000
                ]);
            }
        }

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataSource()
    {
        $datasource = $this->initDataSource($this->datasourceFactory);

        if (!is_object($datasource) || !$datasource instanceof DataSourceInterface) {
            throw new RuntimeException('initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface');
        }

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_delete' => true,
            'template_list' => null,
        ]);

        $resolver->setAllowedTypes('allow_delete', 'bool');
        $resolver->setAllowedTypes('template_list', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($object)
    {
        $this->delete($object);
    }

    /**
     * Initialize DataGrid.
     *
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    abstract protected function initDataGrid(DataGridFactoryInterface $factory);

    /**
     * Initialize DataSource.
     *
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    abstract protected function initDataSource(DataSourceFactoryInterface $factory);
}
