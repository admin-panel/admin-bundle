<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource;

use AdminPanel\Component\DataSource\Exception\DataSourceViewException;
use AdminPanel\Component\DataSource\Field\FieldViewInterface;
use AdminPanel\Component\DataSource\Util\AttributesContainer;

/**
 * {@inheritdoc}
 */
class DataSourceView extends AttributesContainer implements DataSourceViewInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $otherParameters = [];

    /**
     * Array of field views.
     *
     * @var array
     */
    private $fields = [];

    /**
     * Fields iterator.
     *
     * @var \ArrayIterator
     */
    private $iterator;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $result;

    /**
     * @param DataSource $datasource
     */
    public function __construct(DataSource $datasource)
    {
        $this->name = $datasource->getName();
        $this->parameters = $datasource->getParameters();
        $this->otherParameters = $datasource->getOtherParameters();
        $this->result = $datasource->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllParameters()
    {
        return array_merge($this->otherParameters, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getOtherParameters()
    {
        return $this->otherParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function hasField($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function removeField($name)
    {
        if (isset($this->fields[$name])) {
            $this->fields[$name]->setDataSourceView(null);
            unset($this->fields[$name]);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getField($name)
    {
        if (!$this->hasField($name)) {
            throw new DataSourceViewException(sprintf('There\'s no field with name "%s"', $name));
        }
        return $this->fields[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function clearFields()
    {
        $this->fields = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addField(FieldViewInterface $fieldView)
    {
        $name = $fieldView->getName();
        if ($this->hasField($name)) {
            throw new DataSourceViewException(sprintf('There\'s already field with name "%s"', $name));
        }
        $this->fields[$name] = $fieldView;
        $fieldView->setDataSourceView($this);
        $this->iterator = null;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFields(array $fields)
    {
        $this->fields = [];

        foreach ($fields as $field) {
            if (!$field instanceof FieldViewInterface) {
                throw new \InvalidArgumentException('Field must implement AdminPanel\Component\DataSource\Field\FieldViewInterface');
            }

            $this->fields[$field->getName()] = $field;
            $field->setDataSourceView($this);
        }
        $this->iterator = null;

        return $this;
    }

    /**
     * Implementation of \ArrayAccess interface method.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * Implementation of \ArrayAccess interface method.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->fields[$offset];
    }

    /**
     * Implementation of \ArrayAccess interface method.
     *
     * In fact it does nothing - view shouldn't set its fields in this way.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Implementation of \ArrayAccess interface method.
     *
     * In fact it does nothing - view shouldn't unset its fields in this way.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Implementation of \Countable interface method.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->fields);
    }

    /**
     * Implementation of \SeekableIterator interface method.
     *
     * @param integer $position
     */
    public function seek($position)
    {
        $this->checkIterator();

        return $this->iterator->seek($position);
    }

    /**
     * Implementation of \SeekableIterator interface method.
     *
     * @return mixed
     */
    public function current()
    {
        $this->checkIterator();

        return $this->iterator->current();
    }

    /**
     * Implementation of \SeekableIterator interface method.
     *
     * @return mixed
     */
    public function key()
    {
        $this->checkIterator();

        return $this->iterator->key();
    }

    /**
     * Implementation of \SeekableIterator interface method.
     */
    public function next()
    {
        $this->checkIterator();

        return $this->iterator->next();
    }

    /**
     * Implementation of \SeekableIterator interface method.
     */
    public function rewind()
    {
        $this->checkIterator();

        return $this->iterator->rewind();
    }

    /**
     * Implementation of \SeekableIterator interface method.
     *
     * @return bool
     */
    public function valid()
    {
        $this->checkIterator();

        return $this->iterator->valid();
    }

    /**
     * Init iterator.
     */
    private function checkIterator()
    {
        if (!isset($this->iterator)) {
            $this->iterator = new \ArrayIterator($this->fields);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }
}
