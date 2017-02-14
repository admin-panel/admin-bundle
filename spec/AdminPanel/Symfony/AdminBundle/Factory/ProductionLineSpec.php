<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Factory\Worker;
use PhpSpec\ObjectBehavior;

class ProductionLineSpec extends ObjectBehavior
{
    public function it_is_created_with_workers(Worker $workerFoo, Worker $workerBar)
    {
        $this->beConstructedWith([$workerFoo, $workerBar]);
        $this->count()->shouldReturn(2);
        $this->getWorkers()->shouldReturn([$workerFoo, $workerBar]);
    }

    public function it_work_on_element_with_workers(Worker $workerFoo, Worker $workerBar, Element $element)
    {
        $this->beConstructedWith([$workerFoo, $workerBar]);
        $workerBar->mount($element)->shouldBeCalled();
        $workerFoo->mount($element)->shouldBeCalled();

        $this->workOn($element);
    }
}
