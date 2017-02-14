<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormSubmitHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcher $eventDispatcher, FormEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    public function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormSubmitHandler require FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_do_nothing_on_non_POST_request(FormEvent $event, Request $request)
    {
        $request->isMethod('POST')->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_submit_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_return_response_from_request_post_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
