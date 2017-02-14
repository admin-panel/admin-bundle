<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\Request;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use AdminPanel\Symfony\AdminBundle\Tests\Doubles\Entity\Resource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcher $eventDispatcher, FormEvent $event, Router $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    public function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcher $eventDispatcher
    ) {
        $event->getElement()->willReturn($element);
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        ResourceElement $element,
        Router $router
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn([new Resource(), new Resource()]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Entity\\Resource'))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_resource');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'test-resource']);
        $router->generate('fsi_admin_resource', ['element' => 'test-resource'])
            ->willReturn('/resource/test-resource');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    public function it_return_response_from_pre_render_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_return_response_from_pre_entity_save_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_return_response_from_post_entity_save_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        ResourceElement $element
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn([new Resource(), new Resource()]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Entity\\Resource'))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
