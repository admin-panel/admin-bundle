<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

interface DeleteElement extends BatchElement
{
    /**
     * @param mixed $object
     */
    public function delete($object);
}
