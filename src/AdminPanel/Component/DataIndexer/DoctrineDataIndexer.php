<?php

declare(strict_types=1);

namespace AdminPanel\Component\DataIndexer;

use AdminPanel\Component\DataIndexer\DataIndexerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\PropertyAccess\PropertyAccess;
use AdminPanel\Component\DataIndexer\Exception\InvalidArgumentException;
use AdminPanel\Component\DataIndexer\Exception\RuntimeException;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineDataIndexer implements DataIndexerInterface
{
    /**
     * @var string
     */
    protected $separator = "|";

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param ManagerRegistry $registry
     * @param $class
     * @throws \AdminPanel\Component\DataIndexer\Exception\InvalidArgumentException
     * @throws \AdminPanel\Component\DataIndexer\Exception\RuntimeException
     */
    public function __construct(ManagerRegistry $registry, $class)
    {
        $this->manager = $this->tryToGetObjectManager($registry, $class);
        $this->class = $this->tryToGetRootClass($class);
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex($data)
    {
        $this->validateData($data);

        return $this->joinIndexParts($this->getIndexParts($data));
    }

    /**
     * {@inheritdoc}
     */
    protected function validateData($data)
    {
        if (!is_object($data)) {
            throw new InvalidArgumentException("DoctrineDataIndexer can index only objects.");
        }

        if (!is_a($data, $this->class)) {
            throw new InvalidArgumentException(sprintf(
                'DoctrineDataIndexer expects data as instance of "%s" instead of "%s".',
                $this->class,
                get_class($data)
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Returns an array of identifier field names for self::$class.
     *
     * @return array
     */
    private function getIdentifierFieldNames()
    {
        return $this->manager
            ->getClassMetadata($this->class)
            ->getIdentifierFieldNames();
    }

    /**
     * @param ManagerRegistry $registry
     * @param $class
     * @return ObjectManager
     * @throws \AdminPanel\Component\DataIndexer\Exception\InvalidArgumentException
     */
    private function tryToGetObjectManager(ManagerRegistry $registry, $class)
    {
        $manager = $registry->getManagerForClass($class);

        if (!isset($manager)) {
            throw new InvalidArgumentException(sprintf(
                'ManagerRegistry doesn\'t have manager for class "%s".',
                $class
            ));
        }

        return $manager;
    }

    /**
     * @param $class
     * @return string
     * @throws \AdminPanel\Component\DataIndexer\Exception\RuntimeException
     */
    private function tryToGetRootClass($class)
    {
        $classMetadata = $this->manager->getClassMetadata($class);

        if (!$classMetadata instanceof ClassMetadataInfo) {
            throw new RuntimeException("Only Doctrine ORM is supported at the moment");
        }

        if ($classMetadata->isMappedSuperclass) {
            throw new RuntimeException('DoctrineDataIndexer can\'t be created for mapped super class.');
        }

        return $classMetadata->rootEntityName;
    }

    /**
     * @param $object
     * @return array
     */
    private function getIndexParts($object)
    {
        $identifiers = $this->getIdentifierFieldNames();

        $accessor = PropertyAccess::createPropertyAccessor();
        $indexes = array_map(
            function ($identifier) use ($object, $accessor) {
                return $accessor->getValue($object, $identifier);
            },
            $identifiers
        );

        return $indexes;
    }

    /**
     * @param $indexes
     * @return string
     */
    private function joinIndexParts($indexes)
    {
        return implode($this->separator, $indexes);
    }
}
