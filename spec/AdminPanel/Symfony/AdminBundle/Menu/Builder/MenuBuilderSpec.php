<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($dispatcher, 'fsi_admin.menu.tools');
    }

    public function it_should_emit_proper_event(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->dispatch('fsi_admin.menu.tools', Argument::allOf(
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\MenuEvent')
        ))->shouldBeCalled();

        $this->buildMenu()->shouldReturnAnInstanceOf('AdminPanel\Symfony\AdminBundle\Menu\Item\Item');
    }
}
