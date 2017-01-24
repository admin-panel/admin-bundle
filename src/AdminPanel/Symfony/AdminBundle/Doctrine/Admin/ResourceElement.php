<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue;
use AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class ResourceElement extends GenericResourceElement implements Element
{
    use ElementImpl {
        getRepository as protected getElementRepository;
    }

    /**
     * @return \FSi\Bundle\ResourceRepositoryBundle\Doctrine\ResourceRepository
     */
    public function getRepository()
    {
        $repository = $this->getElementRepository();

        if (!$repository instanceof ResourceValueRepository) {
            throw new RuntimeException(sprintf(
                'Repository for class %s must implement \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValueRepository',
                $this->getClassName()
            ));
        }

        return $repository;
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Model\ResourceRepository\ResourceValue $resource
     */
    public function save(ResourceValue $resource)
    {
        $this->getObjectManager()->persist($resource);
        $this->getObjectManager()->flush();
    }
}
