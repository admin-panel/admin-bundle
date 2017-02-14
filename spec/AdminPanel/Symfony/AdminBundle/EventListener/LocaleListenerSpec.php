<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListenerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('en');
    }

    public function it_is_event_subscriber()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    public function it_subscribe_kernel_request_event_before_default_locale_listener()
    {
        $this->getSubscribedEvents()->shouldReturn(
            [KernelEvents::REQUEST => [['onKernelRequest', 17]]]
        );
    }

    public function it_do_nothing_when_request_does_not_have_previous_session(
        GetResponseEvent $event,
        Request $request
    ) {
        $event->getRequest()->shouldBeCalled()->willReturn($request);
        $request->hasPreviousSession()->shouldBeCalled()->willReturn(false);
        $request->getSession()->shouldNotBeCalled();
        $this->onKernelRequest($event);
    }

    public function it_set_default_locale_if_request_does_not_have_locale_param(
        GetResponseEvent $event,
        Request $request,
        ParameterBag $requestAttributes,
        Session $session
    ) {
        $request->attributes = $requestAttributes;
        $event->getRequest()->willReturn($request);
        $request->hasPreviousSession()->willReturn(true);
        $request->getSession()->willReturn($session);
        $session->get('admin_locale', 'en')->willReturn('de');
        $request->setLocale('de')->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    public function it_does_not_set_locale_if_request_alread_has_locale(
        GetResponseEvent $event,
        Request $request,
        ParameterBag $requestAttributes,
        Session $session
    ) {
        $request->attributes = $requestAttributes;
        $requestAttributes->has('_locale')->willReturn(true);
        $event->getRequest()->willReturn($request);
        $request->hasPreviousSession()->willReturn(true);
        $request->getSession()->willReturn($session);
        $request->setLocale(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
