<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceRepositoryContextSpec extends ObjectBehavior
{
    public function let(
        HandlerInterface $handler,
        ResourceElement $element,
        MapBuilder $builder,
        ResourceFormBuilder $resourceFormBuilder,
        Form $form
    ) {
        $builder->getMap()->willReturn([
            'resources' => []
        ]);
        $element->getResourceFormOptions()->willReturn([]);
        $element->getKey()->willReturn('resources');
        $resourceFormBuilder->build($element)->willReturn($form);

        $this->beConstructedWith([$handler], $resourceFormBuilder);
        $this->setElement($element);
    }

    public function it_is_context()
    {
        $this->shouldBeAnInstanceOf('AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface');
    }

    public function it_have_array_data()
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('form');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    public function it_handle_request_with_request_handlers(
        HandlerInterface $handler,
        Request $request
    ) {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\FormEvent'), $request)
            ->shouldBeCalled();

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request
    ) {
        $handler->handleRequest(Argument::type('AdminPanel\Symfony\AdminBundle\Event\FormEvent'), $request)
            ->willReturn(new Response());

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function getMatchers()
    {
        return [
            'haveKeyInArray' => function ($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        ];
    }
}
