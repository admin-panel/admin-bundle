<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ListElement;
use Doctrine\Common\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;

class DoctrineWorkerSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $managerRegistry)
    {
        $this->beConstructedWith($managerRegistry);
    }

    public function it_mount_datagrid_factory_to_elements_that_are_doctrine_elements(
        ListElement $element,
        ManagerRegistry $managerRegistry
    ) {
        $element->setManagerRegistry($managerRegistry)->shouldBeCalled();

        $this->mount($element);
    }
}
