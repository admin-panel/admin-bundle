<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

class DoctrineWorkerSpec extends ObjectBehavior
{
    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     */
    public function let($managerRegistry)
    {
        $this->beConstructedWith($managerRegistry);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ListElement $element
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     */
    public function it_mount_datagrid_factory_to_elements_that_are_doctrine_elements($element, $managerRegistry)
    {
        $element->setManagerRegistry($managerRegistry)->shouldBeCalled();

        $this->mount($element);
    }
}
