<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue;

class MyResource extends GenericResourceElement
{
    public function getKey()
    {
        return 'resources.main_page';
    }

    public function getId()
    {
        return 'main_page';
    }

    public function getName()
    {
        return 'admin.main_page';
    }

    public function getRepository()
    {
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resource
     */
    public function save(ResourceValue $resource)
    {
    }
}
