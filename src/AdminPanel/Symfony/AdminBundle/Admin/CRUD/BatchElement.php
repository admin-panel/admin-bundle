<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;

interface BatchElement extends RedirectableElement
{
    /**
     * This method is called from BatchController after action is confirmed.
     *
     * @throws RequestHandlerException - when cannot apply batch operation to given index ex. element is not found
     * @param mixed $index
     */
    public function apply($index);
}
