<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Functional\Element;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\CRUDElement;
use AdminPanel\Symfony\AdminBundle\Tests\Functional\Entity\User;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CustomTemplateUserElement extends CRUDElement
{
    /**
     * Initialize DataGrid.
     *
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
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
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
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
     * Class name that represent entity. It might be returned in Symfony2 style:
     * FSiDemoBundle:News
     * or as a full class name
     * \FSi\Bundle\DemoBundle\Entity\News
     *
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
            "template_list" => "@app/admin/list.html.twig",
            "template_form" => "@app/admin/form.html.twig"
        ]);
    }

    /**
     * Initialize create Form. This form will be used in createAction in CRUDController.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param mixed $data
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createBuilder(FormType::class, $data, ['data_class' => User::class]);
        $builder->add('username', TextType::class, ['required' => true]);

        return $builder->getForm();
    }
}