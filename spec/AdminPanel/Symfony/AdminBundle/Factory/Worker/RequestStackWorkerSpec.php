<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin\RequestStackAwareElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackWorkerSpec extends ObjectBehavior
{
    public function let(RequestStack $requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    public function it_mount_request_stack_to_elements_that_are_request_stack_aware(
        RequestStackAwareElement $element,
        RequestStack $requestStack
    ) {
        $element->setRequestStack($requestStack)->shouldBeCalled();

        $this->mount($element);
    }
}
