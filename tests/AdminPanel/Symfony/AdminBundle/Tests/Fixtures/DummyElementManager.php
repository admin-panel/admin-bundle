<?php

declare (strict_types = 1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Fixtures;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\Manager\Visitor;
use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;

final class DummyElementManager implements ManagerInterface
{
    private $elements = [];

    public function __construct($elements)
    {
        $this->elements = $elements;
    }

    /**
     * @param Element $element
     */
    public function addElement(Element $element)
    {
        $this->elements[$element->getId()] = $element;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasElement($id)
    {
        return isset($this->elements[$id]);
    }

    /**
     * @param string $id
     * @return Element
     */
    public function getElement($id)
    {
        return $this->elements[$id] ?? null;
    }

    /**
     * @param int $id
     */
    public function removeElement($id)
    {
        unset($this->elements[$id]);
    }

    /**
     * @return Element[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param Visitor $visitor
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return true;
    }
}