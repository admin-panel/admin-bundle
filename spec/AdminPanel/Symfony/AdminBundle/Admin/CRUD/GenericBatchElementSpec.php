<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericBatchElementSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyBatch');
        $this->beConstructedWith([]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericBatchElement');
    }

    public function it_is_delete_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement');
    }

    public function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    public function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('admin_panel_batch');
    }
}
