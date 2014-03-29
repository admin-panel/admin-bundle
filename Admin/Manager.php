<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class Manager implements ManagerInterface
{
    /**
     * @var \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    protected $elements;

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return \FSi\Bundle\AdminBundle\Admin\Manager
     */
    public function addElement(ElementInterface $element)
    {
        $this->elements[$element->getId()] = $element;

        return $this;
    }

    public function __construct()
    {
        $this->elements = array();
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasElement($id)
    {
        return array_key_exists($id, $this->elements);
    }

    /**
     * @param string $id
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface
     */
    public function getElement($id)
    {
        return $this->elements[$id];
    }

    /**
     * @param int $id
     */
    public function removeElement($id)
    {
        unset($this->elements[$id]);
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElements()
    {
        return $this->elements;
    }
}
