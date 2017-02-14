<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\Manager\Visitor;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    public function it_remove_element_by_id(Element $element)
    {
        $element->getId()->willReturn('foo');
        $this->addElement($element);

        $this->hasElement('foo')->shouldReturn(true);
        $this->removeElement('foo');
        $this->hasElement('foo')->shouldReturn(false);
    }

    public function it_accept_visitors(Visitor $visitor)
    {
        $visitor->visitManager($this)->shouldBeCalled();
        $this->accept($visitor);
    }
}
