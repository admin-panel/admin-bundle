<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Element;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ListElement;
use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use AdminPanel\Component\DataGrid\DataGridFactoryInterface;
use AdminPanel\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CustomTemplateUserElement extends ListElement
{
    /**
     * Initialize DataGrid.
     *
     * @param \AdminPanel\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \AdminPanel\Component\DataGrid\DataGridInterface
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \AdminPanel\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid(
            $this->getId() // this is the ID of the element's datagrid
        );
        $datagrid->addColumn('username', 'text', [
            'label' => 'Username'
        ]);
        $datagrid->addColumn('hasNewsletter', 'boolean', [
            'label' => 'Has newsletter?'
        ]);

        return $datagrid;
    }

    /**
     * Initialize DataSource.
     *
     * @param \AdminPanel\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \AdminPanel\Component\DataSource\DataSourceInterface
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \AdminPanel\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource(
            'doctrine',
            ['entity' => $this->getClassName()],
            $this->getId() // this is the ID of the element's datasource
        );
        $datasource->setMaxResults(10);

        return $datasource;
    }

    /**
     * ID will appear in routes:
     * - http://example.com/admin/list/{name}
     * - http://example.com/admin/form/{name}
     * etc.
     *
     * @return string
     */
    public function getId()
    {
        return 'admin_custom_template_users';
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return User::class;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            "allow_delete" => false,
            "template_list" => "@app/admin/list.html.twig"
        ]);
    }
}