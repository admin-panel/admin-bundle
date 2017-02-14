<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use PhpSpec\ObjectBehavior;
use Doctrine\Common\Persistence\ManagerRegistry;

class ResourceElementSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry)
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyResourceElement');
        $this->setManagerRegistry($registry);
    }

    public function it_return_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ResourceValueRepository $repository
    ) {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $om->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->getRepository()->shouldReturn($repository);
    }

    public function it_throws_exception_when_repository_does_not_implement_resource_value_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository
    ) {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $registry->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->shouldThrow()->during('getRepository');
    }
}
