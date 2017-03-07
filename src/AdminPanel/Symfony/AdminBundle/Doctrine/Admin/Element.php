<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

interface Element extends DoctrineAwareInterface
{
    /**
     * Class name that represent entity. It might be returned in Symfony2 style:
     * AdminPanelDemoBundle:News
     * or as a full class name
     * \AdminPanel\Bundle\DemoBundle\Entity\News
     *
     * @return string
     */
    public function getClassName();

    /**
     * @return ObjectManager
     * @throws RuntimeException
     */
    public function getObjectManager();

    /**
     * @return ObjectRepository
     */
    public function getRepository();

    /**
     * @param ManagerRegistry $registry
     * @return null
     */
    public function setManagerRegistry(ManagerRegistry $registry);
}
