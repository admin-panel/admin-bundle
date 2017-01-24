<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;
use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue;

interface Element extends RedirectableElement
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return array
     */
    public function getResourceFormOptions();

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resource
     */
    public function save(ResourceValue $resource);

    /**
     * @return \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository
     */
    public function getRepository();
}
