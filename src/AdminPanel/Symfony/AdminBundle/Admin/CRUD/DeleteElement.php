<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;

interface DeleteElement extends BatchElement
{
    /**
     * @throws RequestHandlerException - when cannot apply delete operation to given index ex. element is not found
     * @param mixed $index
     */
    public function delete($index);
}
