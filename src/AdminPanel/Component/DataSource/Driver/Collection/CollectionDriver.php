<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataSource\Driver\Collection;

use Doctrine\Common\Collections\Criteria;
use AdminPanel\Component\DataSource\Driver\DriverAbstract;
use AdminPanel\Component\DataSource\Driver\Collection\Exception\CollectionDriverException;
use AdminPanel\Component\DataSource\Driver\Collection\CollectionFieldInterface;
use AdminPanel\Component\DataSource\Driver\Collection\CollectionResult;

class CollectionDriver extends DriverAbstract
{
    private $collection;

    /**
     * Criteria available during preGetResult event.
     *
     * @var \Doctrine\Common\Collections\Criteria
     */
    private $currentCriteria;

    /**
     * @param array $extensions
     * @param array $collection
     */
    public function __construct(array $extensions, array $collection)
    {
        parent::__construct($extensions);
        $this->collection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'collection';
    }

    protected function initResult()
    {
        $this->currentCriteria = new Criteria();
    }

    /**
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \AdminPanel\Component\DataSource\Driver\Collection\CollectionResult
     * @throws \AdminPanel\Component\DataSource\Driver\Collection\Exception\CollectionDriverException
     */
    protected function buildResult($fields, $first, $max)
    {
        foreach ($fields as $field) {
            if (!$field instanceof CollectionFieldInterface) {
                throw new CollectionDriverException(sprintf('All fields must be instances of AdminPanel\Component\DataSource\Driver\Collection\CollectionFieldInterface.'));
            }

            $field->buildCriteria($this->currentCriteria);
        }

        if ($max > 0) {
            $this->currentCriteria->setMaxResults($max);
            $this->currentCriteria->setFirstResult($first);
        }

        return new CollectionResult($this->collection, $this->currentCriteria);
    }

    /**
     * Returns criteria.
     *
     * If criteria is set to null (so when getResult method is NOT executed at the moment) exception is throwed.
     *
     * @throws \AdminPanel\Component\DataSource\Driver\Collection\Exception\CollectionDriverException
     * @return \Doctrine\Common\Collections\Criteria
     */
    public function getCriteria()
    {
        if (!isset($this->currentCriteria)) {
            throw new CollectionDriverException('Criteria is accessible only during preGetResult event.');
        }

        return $this->currentCriteria;
    }
}
