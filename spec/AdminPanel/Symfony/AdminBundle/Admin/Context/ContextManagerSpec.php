<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use PhpSpec\ObjectBehavior;

class ContextManagerSpec extends ObjectBehavior
{
    public function let(ContextInterface $context)
    {
        $this->beConstructedWith([$context]);
    }

    public function it_build_context_for_element(Element $element, ContextInterface $context)
    {
        $context->supports('route_name', $element)->willReturn(true);
        $context->setElement($element)->shouldBeCalled();

        $this->createContext('route_name', $element)->shouldReturn($context);
    }

    public function it_return_null_when_context_builders_do_not_support_element(Element $element, ContextInterface $context)
    {
        $context->supports('route_name', $element)->willReturn(false);
        $context->setElement($element)->shouldNotBeCalled();

        $this->createContext('route_name', $element)->shouldReturn(null);
    }
}
