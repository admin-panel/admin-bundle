<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;

class ListElementSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry, ObjectManager $om)
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\Doctrine\MyListElement');
        $this->beConstructedWith([]);

        $registry->getManagerForClass('AdminPanelDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
    }

    public function it_should_return_object_manager(ObjectManager $om)
    {
        $this->getObjectManager()->shouldReturn($om);
    }

    public function it_should_return_object_repository(ObjectManager $om, ObjectRepository $repository)
    {
        $om->getRepository('AdminPanelDemoBundle:Entity')->willReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    public function it_should_have_doctrine_data_indexer(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository,
        ClassMetadata $metadata
    ) {
        $registry->getManagerForClass('AdminPanel/Bundle/DemoBundle/Entity/Entity')->willReturn($om);
        $om->getRepository('AdminPanelDemoBundle:Entity')->willReturn($repository);
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'AdminPanel/Bundle/DemoBundle/Entity/Entity';
        $om->getClassMetadata('AdminPanel/Bundle/DemoBundle/Entity/Entity')->willReturn($metadata);

        $repository->getClassName()->willReturn('AdminPanel/Bundle/DemoBundle/Entity/Entity');

        $this->setManagerRegistry($registry);
        $this->getDataIndexer()->shouldReturnAnInstanceOf('AdminPanel\Component\DataIndexer\DoctrineDataIndexer');
    }
}
