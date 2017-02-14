<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataSourceBindParametersHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcher $eventDispatcher, ListEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    public function it_throw_exception_for_non_list_event(AdminEvent $event, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataSourceBindParametersHandler require ListEvent"
            )
        )->during('handleRequest', [$event, $request]);
    }

    public function it_bind_request_to_datasource_and_dispatch_events(
        ListEvent $event,
        DataSource $dataSource,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_pre_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_return_response_from_post_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        DataSource $dataSource
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
