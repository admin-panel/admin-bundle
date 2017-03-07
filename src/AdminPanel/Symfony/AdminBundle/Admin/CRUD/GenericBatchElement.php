<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\AbstractElement;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericBatchElement extends AbstractElement implements BatchElement
{
    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'admin_panel_batch';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return 'admin_panel_index';
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return [];
    }
}
