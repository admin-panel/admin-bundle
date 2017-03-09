<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;

interface BatchElement extends RedirectableElement
{
    /**
     * This method is called from BatchController after action is confirmed.
     *
     * @param mixed $object
     */
    public function apply($object);

    /**
     * This method is called from BatchFormValidRequestHandler to get object
     * @param $index
     * @return mixed
     */
    public function getObject($index);
}
