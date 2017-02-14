<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Component\DataSource\DataSource;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGrid;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridBindDataHandlerSpec extends ObjectBehavior
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
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridBindDataHandler require ListEvent"
            )
        )->during('handleRequest', [$event, $request]);
    }

    public function it_do_nothing_when_request_is_not_a_POST(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_do_nothing_when_request_is_not_a_POST_and_return_respone_from_pre_render_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_bind_data_at_datagrid_for_POST_request(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        DataGrid $dataGrid,
        DataSource $dataSource,
        ListElement $element
    ) {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event)
            ->shouldBeCalled();

        $event->getElement()->willReturn($element);
        $element->saveDataGrid()->shouldBeCalled();
        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();
        $dataSource->getResult()->willReturn([1]);
        $dataGrid->setData([1])->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_datagrid_pre_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    public function it_return_response_from_datagrid_post_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        DataGrid $dataGrid
    ) {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
