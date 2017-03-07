<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Doctrine;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\DeleteElement;

class MyDeleteElement extends DeleteElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'AdminPanelDemoBundle:Entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'my_entity_batch';
    }
}
